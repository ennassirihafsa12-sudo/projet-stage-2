<?php

namespace App\Console\Commands;

use App\Enums\MarcheStatut;
use App\Models\Marche;
use Illuminate\Console\Command;

class FixMarcheStatutsCommand extends Command
{
    protected $signature = 'marches:fix-statuts';

    protected $description = 'Corrige les statuts de marchés invalides en base (ex: "0")';

    public function handle(): int
    {
        $valid = array_map(fn (MarcheStatut $s) => $s->value, MarcheStatut::cases());
        $fixed = 0;

        Marche::query()->each(function (Marche $marche) use ($valid, &$fixed): void {
            $raw = $marche->getRawOriginal('statut');

            if (in_array($raw, $valid, true)) {
                return;
            }

            $marche->statut = MarcheStatut::EnAttente;
            $marche->recalculerStatut();
            $marche->save();
            $fixed++;
        });

        $this->info("{$fixed} marché(s) corrigé(s).");

        return self::SUCCESS;
    }
}
