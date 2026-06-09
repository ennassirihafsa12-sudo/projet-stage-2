<?php

namespace App\Enums;

enum NotificationType: string
{
    case Urgent = 'urgent';
    case ASurveiller = 'a_surveiller';
    case Information = 'information';
    case EnRetard = 'en_retard';

    public function label(): string
    {
        return match ($this) {
            self::Urgent => 'Urgent',
            self::ASurveiller => 'À surveiller',
            self::Information => 'Information',
            self::EnRetard => 'En retard',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Urgent => 'yellow',
            self::ASurveiller => 'orange',
            self::Information => 'blue',
            self::EnRetard => 'red',
        };
    }
}
