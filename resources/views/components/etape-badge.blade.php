@props(['statut'])

@php
    $statut = $statut instanceof \App\Enums\EtapeStatut ? $statut : \App\Enums\EtapeStatut::from($statut);
    $classes = match ($statut) {
        \App\Enums\EtapeStatut::Termine => 'bg-green-100 text-green-800',
        \App\Enums\EtapeStatut::EnAttente => 'bg-yellow-100 text-yellow-800',
        \App\Enums\EtapeStatut::PasCommencee => 'bg-slate-100 text-slate-600',
    };
@endphp

<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $classes }}">
    {{ $statut->label() }}
</span>
