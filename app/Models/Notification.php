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

        $now = now()->startOfMinute();
        $target = $this->echeance_at->copy()->startOfMinute();

        $diffInMinutes = $now->diffInMinutes($target, false);
        $isPast = $diffInMinutes < 0;
        $absMinutes = abs($diffInMinutes);

        $days = (int) floor($absMinutes / (24 * 60));
        $hours = (int) floor(($absMinutes % (24 * 60)) / 60);
        $minutes = (int) ($absMinutes % 60);

        $parts = [];
        if ($days > 0) {
            $parts[] = $days.' jour'.($days > 1 ? 's' : '');
        }
        if ($hours > 0) {
            $parts[] = $hours.' heure'.($hours > 1 ? 's' : '');
        }
        if ($minutes > 0 || empty($parts)) {
            $parts[] = $minutes.' minute'.($minutes > 1 ? 's' : '');
        }

        $durationString = '';
        if (count($parts) === 1) {
            $durationString = $parts[0];
        } elseif (count($parts) > 1) {
            $last = array_pop($parts);
            $durationString = implode(', ', $parts).' et '.$last;
        }

        if ($isPast) {
            return 'En retard de '.$durationString;
        }

        return 'Dans '.$durationString;
    }
}
