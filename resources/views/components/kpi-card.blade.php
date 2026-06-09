@props(['label', 'value', 'color' => 'marine'])

@php
    $borders = [
        'marine' => 'border-marine',
        'green' => 'border-green-600',
        'yellow' => 'border-yellow-500',
        'red' => 'border-red-600',
        'dore' => 'border-dore',
    ];
    $values = [
        'marine' => 'text-marine',
        'green' => 'text-green-700',
        'yellow' => 'text-yellow-700',
        'red' => 'text-red-700',
        'dore' => 'text-dore',
    ];
@endphp

<div class="card-panel border-s-4 p-6 {{ $borders[$color] ?? $borders['marine'] }} {{ app()->getLocale() === 'ar' ? 'border-s-0 border-e-4' : '' }}">
    <p class="text-sm font-medium text-gris-moyen">{{ $label }}</p>
    <p class="mt-2 text-3xl font-bold {{ $values[$color] ?? $values['marine'] }}">{{ $value }}</p>
</div>
