<?php

namespace App\Models;

use App\Enums\NotificationType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'marche_id',
        'titre',
        'message',
        'type',
        'echeance_at',
        'lu',
    ];

    protected function casts(): array
    {
        return [
            'type' => NotificationType::class,
            'echeance_at' => 'datetime',
            'lu' => 'boolean',
        ];
    }

    public function marche(): BelongsTo
    {
        return $this->belongsTo(Marche::class);
    }

    public function tempsRelatif(): string
    {
        if (! $this->echeance_at) {
            return $this->created_at->diffForHumans();
        }

        $diff = now()->diffInHours($this->echeance_at, false);

        if ($diff < 0) {
            return 'En retard depuis '.abs($diff).' heures';
        }

        if ($diff < 48) {
            return 'Dans '.$diff.' heures';
        }

        return 'Dans '.$this->echeance_at->diffInDays(now()).' jours';
    }
}
