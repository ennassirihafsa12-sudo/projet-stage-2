<?php

namespace App\Enums;

enum LettreType: string
{
    case Acceptation = 'acceptation';
    case Refus = 'refus';

    public function label(): string
    {
        return __('app.lettres.types.'.$this->value);
    }

    public function titreDocument(): string
    {
        return __('app.lettres.titles.'.$this->value);
    }
}
