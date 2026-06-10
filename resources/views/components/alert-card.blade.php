@props(['titre', 'temps', 'type', 'message' => null, 'marche' => null])

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

<details class="group rounded-xl border-l-4 bg-white p-5 shadow-sm {{ $border }} cursor-pointer list-none [&::-webkit-details-marker]:hidden">
    <summary class="flex items-start gap-4 w-full list-none [&::-webkit-details-marker]:hidden">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-600">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="flex-1">
            <h3 class="font-medium text-slate-900 flex items-center gap-2">
                {{ $titre }}
                <svg class="h-4 w-4 text-slate-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </h3>
            <p class="mt-1 text-sm text-slate-500">{{ $temps }}</p>
        </div>
        <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badge }}">{{ $notificationType->label() }}</span>
    </summary>
    
    <div class="mt-4 border-t border-slate-100 pt-4 pl-14 text-sm text-slate-600">
        @if($message)
            <p class="font-medium text-slate-800">{{ $message }}</p>
        @endif
        @if($marche)
            <div class="mt-3 flex items-center justify-between rounded-lg bg-slate-50 p-3">
                <div>
                    <span class="text-xs text-slate-500">Marché lié</span>
                    <p class="font-semibold text-marine">{{ $marche->numero }} — {{ Str::limit($marche->objet, 60) }}</p>
                </div>
                <a href="{{ route('marches.show', ['marche' => $marche->id]) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-dore px-3 py-1.5 text-xs font-bold text-marine shadow-sm hover:bg-dore-hover">
                    Voir détails du marché
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        @endif
    </div>
</details>
