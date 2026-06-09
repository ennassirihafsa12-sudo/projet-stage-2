<?php

namespace App\Models;

use App\Enums\EtapeNom;
use App\Enums\EtapeStatut;
use App\Enums\NotificationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Etape extends Model
{
    protected $fillable = [
        'marche_id',
        'nom',
        'ordre',
        'date_prevue',
        'date_reelle',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'nom' => EtapeNom::class,
            'date_prevue' => 'date',
            'date_reelle' => 'date',
            'statut' => EtapeStatut::class,
        ];
    }

    public function marche(): BelongsTo
    {
        return $this->belongsTo(Marche::class);
    }

    public function terminer(): void
    {
        $this->update([
            'statut' => EtapeStatut::Termine,
            'date_reelle' => now()->toDateString(),
        ]);

        $next = $this->marche->etapes()
            ->where('ordre', '>', $this->ordre)
            ->orderBy('ordre')
            ->first();

        if ($next) {
            $next->update(['statut' => EtapeStatut::EnAttente]);
        }

        $this->marche->notifications()->create([
            'titre' => 'Étape terminée : '.$this->nom->label(),
            'message' => 'L\'étape « '.$this->nom->label().' » du marché '.$this->marche->numero.' est terminée.',
            'type' => NotificationType::Information,
        ]);

        $this->marche->recalculerStatut();
        $this->marche->genererRappels();
    }
}
