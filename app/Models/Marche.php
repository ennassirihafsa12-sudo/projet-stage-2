<?php

namespace App\Models;

use App\Enums\EtapeNom;
use App\Enums\EtapeStatut;
use App\Enums\MarcheStatut;
use App\Enums\NotificationType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marche extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero',
        'objet',
        'date_publication',
        'date_ouverture_plis',
        'validite_offre_jours',
        'entreprise',
        'montant_estimatif',
        'responsable',
        'description',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'date_publication' => 'date',
            'date_ouverture_plis' => 'date',
            'montant_estimatif' => 'decimal:2',
            'statut' => MarcheStatut::class,
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Marche $marche): void {
            if ($marche->statut === null) {
                $marche->statut = MarcheStatut::EnAttente;

                return;
            }

            if ($marche->statut instanceof MarcheStatut) {
                return;
            }

            $resolved = MarcheStatut::tryFrom((string) $marche->statut);

            $marche->statut = $resolved ?? MarcheStatut::EnAttente;
        });
    }

    public function etapes(): HasMany
    {
        return $this->hasMany(Etape::class)->orderBy('ordre');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function lettres(): HasMany
    {
        return $this->hasMany(Lettre::class);
    }

    public function etapeCourante(): ?Etape
    {
        return $this->etapes()
            ->whereIn('statut', [EtapeStatut::EnAttente, EtapeStatut::PasCommencee])
            ->orderBy('ordre')
            ->first();
    }

    public function etapeCouranteLabel(): string
    {
        return $this->etapeCourante()?->nom->label() ?? 'Terminé';
    }

    public function tempsRestant(): ?string
    {
        $etape = $this->etapeCourante();
        if (! $etape?->date_prevue) {
            return null;
        }

        $diff = now()->startOfDay()->diffInDays($etape->date_prevue, false);

        if ($diff < 0) {
            return abs($diff).' jour'.(abs($diff) > 1 ? 's' : '').' de retard';
        }

        if ($diff === 0) {
            return "Aujourd'hui";
        }

        if ($diff === 1) {
            return '1 jour';
        }

        if ($diff <= 2) {
            $hours = now()->diffInHours($etape->date_prevue->endOfDay(), false);

            return $hours > 0 && $hours <= 48 ? $hours.'h' : $diff.' jours';
        }

        return $diff.' jours';
    }

    public function recalculerStatut(): void
    {
        $etape = $this->etapeCourante();

        if (! $etape) {
            $this->update(['statut' => MarcheStatut::Termine]);

            return;
        }

        if ($etape->date_prevue && $etape->date_prevue->isPast()) {
            $this->update(['statut' => MarcheStatut::EnRetard]);

            return;
        }

        if ($etape->statut === EtapeStatut::EnAttente) {
            $this->update(['statut' => MarcheStatut::EnCours]);

            return;
        }

        $this->update(['statut' => MarcheStatut::EnAttente]);
    }

    public function montantFormate(): string
    {
        if (! $this->montant_estimatif) {
            return '—';
        }

        return number_format((float) $this->montant_estimatif, 0, ',', ' ').' FCFA';
    }

    public static function creerEtapesParDefaut(self $marche): void
    {
        $dates = [
            EtapeNom::Publication->value => $marche->date_publication,
            EtapeNom::OuverturePlis->value => $marche->date_ouverture_plis,
            EtapeNom::AnalyseOffres->value => $marche->date_ouverture_plis->copy()->addDays(14),
            EtapeNom::Attribution->value => $marche->date_ouverture_plis->copy()->addDays(30),
        ];

        $firstPending = true;

        foreach (EtapeNom::ordered() as $nom) {
            $statut = EtapeStatut::PasCommencee;

            if ($nom === EtapeNom::Publication) {
                $statut = EtapeStatut::Termine;
            } elseif ($firstPending) {
                $statut = EtapeStatut::EnAttente;
                $firstPending = false;
            }

            $marche->etapes()->create([
                'nom' => $nom,
                'ordre' => $nom->ordre(),
                'date_prevue' => $dates[$nom->value],
                'date_reelle' => $nom === EtapeNom::Publication ? $marche->date_publication : null,
                'statut' => $statut,
            ]);
        }

        $marche->recalculerStatut();
        $marche->genererRappels();
    }

    public function genererRappels(): void
    {
        foreach ($this->etapes()->where('statut', '!=', EtapeStatut::Termine)->get() as $etape) {
            if (! $etape->date_prevue) {
                continue;
            }

            $type = NotificationType::Information;
            if ($etape->date_prevue->isPast()) {
                $type = NotificationType::EnRetard;
            } elseif ($etape->date_prevue->diffInDays(now()) <= 2) {
                $type = NotificationType::Urgent;
            } elseif ($etape->date_prevue->diffInDays(now()) <= 7) {
                $type = NotificationType::ASurveiller;
            }

            $this->notifications()->updateOrCreate(
                [
                    'titre' => $etape->nom->label().' - Marché '.$this->numero,
                ],
                [
                    'message' => 'Échéance pour l\'étape « '.$etape->nom->label().' » du marché '.$this->numero.'.',
                    'type' => $type,
                    'echeance_at' => $etape->date_prevue,
                ]
            );
        }
    }
}
