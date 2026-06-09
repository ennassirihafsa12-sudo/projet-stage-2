<?php

namespace App\Enums;

enum MarcheStatut: string
{
    case EnCours = 'en_cours';
    case EnAttente = 'en_attente';
    case EnRetard = 'en_retard';
    case Termine = 'termine';

    public function label(): string
    {
        return match ($this) {
            self::EnCours => 'En cours',
            self::EnAttente => 'En attente',
            self::EnRetard => 'En retard',
            self::Termine => 'Terminé',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::EnCours => 'green',
            self::EnAttente => 'yellow',
            self::EnRetard => 'red',
            self::Termine => 'blue',
        };
    }
}
