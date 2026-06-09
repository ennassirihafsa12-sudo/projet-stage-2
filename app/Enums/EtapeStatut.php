<?php

namespace App\Enums;

enum EtapeStatut: string
{
    case Termine = 'termine';
    case EnAttente = 'en_attente';
    case PasCommencee = 'pas_commencee';

    public function label(): string
    {
        return match ($this) {
            self::Termine => 'Terminé',
            self::EnAttente => 'En attente',
            self::PasCommencee => 'Pas commencée',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Termine => 'green',
            self::EnAttente => 'yellow',
            self::PasCommencee => 'gray',
        };
    }
}
