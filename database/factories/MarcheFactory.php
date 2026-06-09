<?php

namespace Database\Factories;

use App\Enums\EtapeStatut;
use App\Enums\MarcheStatut;
use App\Models\Marche;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Marche>
 */
class MarcheFactory extends Factory
{
    protected $model = Marche::class;

    public function definition(): array
    {
        $publication = fake()->dateTimeBetween('-4 months', '-2 weeks');
        $ouverture = fake()->dateTimeBetween($publication, '+6 weeks');

        return [
            'numero' => fake()->unique()->numerify('##/').now()->format('Y'),
            'objet' => ucfirst(fake()->words(5, true)),
            'date_publication' => $publication,
            'date_ouverture_plis' => $ouverture,
            'validite_offre_jours' => fake()->randomElement([30, 45, 60, 90]),
            'entreprise' => fake()->optional(0.8)->company(),
            'montant_estimatif' => fake()->numberBetween(5_000_000, 200_000_000),
            'responsable' => fake()->name(),
            'description' => fake()->paragraph(),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Marche $marche): void {
            if (! $marche->statut instanceof MarcheStatut) {
                $marche->statut = MarcheStatut::EnCours;
            }
        })->afterCreating(function (Marche $marche): void {
            if ($marche->etapes()->doesntExist()) {
                Marche::creerEtapesParDefaut($marche);
            }
        });
    }

    public function enCours(): static
    {
        return $this->afterMaking(fn (Marche $marche) => $marche->statut = MarcheStatut::EnCours);
    }

    public function enAttente(): static
    {
        return $this->afterMaking(fn (Marche $marche) => $marche->statut = MarcheStatut::EnAttente);
    }

    public function enRetard(): static
    {
        return $this->afterMaking(fn (Marche $marche) => $marche->statut = MarcheStatut::EnRetard)
            ->afterCreating(function (Marche $marche): void {
                $marche->etapes()->where('statut', EtapeStatut::EnAttente)->first()?->update([
                    'date_prevue' => now()->subDays(3),
                ]);
                $marche->recalculerStatut();
            });
    }

    public function termine(): static
    {
        return $this->afterMaking(fn (Marche $marche) => $marche->statut = MarcheStatut::Termine)
            ->afterCreating(function (Marche $marche): void {
                $marche->etapes()->update([
                    'statut' => EtapeStatut::Termine,
                    'date_reelle' => now()->subMonths(2),
                ]);
                $marche->statut = MarcheStatut::Termine;
                $marche->save();
            });
    }
}
