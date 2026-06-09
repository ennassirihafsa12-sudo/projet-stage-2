<?php

namespace Database\Factories;

use App\Enums\NotificationType;
use App\Models\Marche;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'marche_id' => Marche::factory(),
            'titre' => ucfirst(fake()->sentence(6)),
            'message' => fake()->paragraph(),
            'type' => fake()->randomElement(NotificationType::cases()),
            'echeance_at' => fake()->optional()->dateTimeBetween('now', '+2 weeks'),
            'lu' => false,
        ];
    }

    public function urgent(): static
    {
        return $this->state(fn () => [
            'type' => NotificationType::Urgent,
            'echeance_at' => now()->addHours(48),
        ]);
    }

    public function aSurveiller(): static
    {
        return $this->state(fn () => [
            'type' => NotificationType::ASurveiller,
        ]);
    }
}
