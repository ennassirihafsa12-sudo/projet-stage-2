<?php

namespace Database\Seeders;

use App\Models\Marche;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create();
        User::factory()->agent()->create();

        $marcheEnCours = Marche::factory()->enCours()->create([
            'numero' => '12/2026',
            'objet' => 'Travaux bâtiment administratif',
            'date_publication' => now()->subDays(30),
            'date_ouverture_plis' => now()->addDays(2),
            'validite_offre_jours' => 90,
            'entreprise' => 'BTP Sénégal SA',
            'montant_estimatif' => 125_000_000,
            'responsable' => 'M. Diop',
            'description' => 'Réhabilitation du bâtiment principal de la mairie.',
        ]);

        $marcheEnRetard = Marche::factory()->enRetard()->create([
            'numero' => '08/2026',
            'objet' => 'Fourniture de matériel informatique',
            'date_publication' => now()->subDays(45),
            'date_ouverture_plis' => now()->subDays(5),
            'validite_offre_jours' => 60,
            'entreprise' => 'Tech Solutions',
            'montant_estimatif' => 45_000_000,
            'responsable' => 'Mme Fall',
            'description' => 'Acquisition d\'ordinateurs et périphériques.',
        ]);

        Marche::factory()->enAttente()->create([
            'numero' => '05/2026',
            'objet' => 'Entretien espaces verts',
            'date_publication' => now()->subDays(60),
            'date_ouverture_plis' => now()->addDays(15),
            'validite_offre_jours' => 45,
            'entreprise' => null,
            'montant_estimatif' => 12_000_000,
            'responsable' => 'M. Ndiaye',
            'description' => 'Contrat annuel d\'entretien des parcs.',
        ]);

        Marche::factory()->termine()->create([
            'numero' => '03/2026',
            'objet' => 'Étude faisabilité voirie',
            'date_publication' => now()->subMonths(4),
            'date_ouverture_plis' => now()->subMonths(3),
            'validite_offre_jours' => 30,
            'entreprise' => 'Ingénierie Plus',
            'montant_estimatif' => 8_500_000,
            'responsable' => 'M. Sow',
            'description' => 'Mission d\'étude pour extension routière.',
        ]);

        Notification::factory()->urgent()->create([
            'marche_id' => $marcheEnCours->id,
            'titre' => 'Rappel : Ouverture des plis - Marché 12/2026',
            'message' => 'L\'ouverture des plis du marché 12/2026 est prévue dans 48 heures.',
        ]);

        Notification::factory()->aSurveiller()->create([
            'marche_id' => $marcheEnRetard->id,
            'titre' => 'Attention : Fin de validité des offres',
            'message' => 'La validité des offres du marché 08/2026 expire bientôt.',
        ]);
    }
}
