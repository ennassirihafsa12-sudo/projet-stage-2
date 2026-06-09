<?php

namespace App\Models;

use App\Enums\LettreType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lettre extends Model
{
    protected $fillable = [
        'marche_id',
        'type',
        'entreprise',
        'format',
        'fichier_path',
    ];

    protected function casts(): array
    {
        return [
            'type' => LettreType::class,
        ];
    }

    public function marche(): BelongsTo
    {
        return $this->belongsTo(Marche::class);
    }
}
