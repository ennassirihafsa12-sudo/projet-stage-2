@props(['titre', 'temps', 'type'])

@php
    $notificationType = $type instanceof \App\Enums\NotificationType ? $type : \App\Enums\NotificationType::from($type);
    $border = match ($notificationType) {
        \App\Enums\NotificationType::Urgent => 'border-yellow-400',
        \App\Enums\NotificationType::ASurveiller => 'border-orange-400',
        \App\Enums\NotificationType::Information => 'border-blue-400',
        \App\Enums\NotificationType::EnRetard => 'border-red-400',
    };
    $badge = match ($notificationType) {
        \App\Enums\NotificationType::Urgent => 'bg-yellow-100 text-yellow-800',
        \App\Enums\NotificationType::ASurveiller => 'bg-orange-100 text-orange-800',
        \App\Enums\NotificationType::Information => 'bg-blue-100 text-blue-800',
        \App\Enums\NotificationType::EnRetard => 'bg-red-100 text-red-800',
    };
@endphp

<div class="flex items-start gap-4 rounded-xl border-l-4 bg-white p-5 shadow-sm {{ $border }}">
    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="flex-1">
        <h3 class="font-medium text-slate-900">{{ $titre }}</h3>
        <p class="mt-1 text-sm text-slate-500">{{ $temps }}</p>
    </div>
    <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badge }}">{{ $notificationType->label() }}</span>
</div>
