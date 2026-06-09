<?php

namespace App\Enums;

enum EtapeNom: string
{
    case Publication = 'publication';
    case OuverturePlis = 'ouverture_plis';
    case AnalyseOffres = 'analyse_offres';
    case Attribution = 'attribution';

    public function label(): string
    {
        return match ($this) {
            self::Publication => 'Publication',
            self::OuverturePlis => 'Ouverture des plis',
            self::AnalyseOffres => 'Analyse des offres',
            self::Attribution => 'Attribution',
        };
    }

    public function ordre(): int
    {
        return match ($this) {
            self::Publication => 1,
            self::OuverturePlis => 2,
            self::AnalyseOffres => 3,
            self::Attribution => 4,
        };
    }

    /** @return list<self> */
    public static function ordered(): array
    {
        return [
            self::Publication,
            self::OuverturePlis,
            self::AnalyseOffres,
            self::Attribution,
        ];
    }
}
