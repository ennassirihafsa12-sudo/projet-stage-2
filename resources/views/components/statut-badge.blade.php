@props(['statut'])

@php
    use App\Enums\MarcheStatut;

    $statutEnum = $statut instanceof MarcheStatut
        ? $statut
        : (MarcheStatut::tryFrom(is_scalar($statut) ? (string) $statut : '') ?? MarcheStatut::EnAttente);

    $classes = match ($statutEnum) {
        MarcheStatut::EnCours => 'bg-green-100 text-green-800',
        MarcheStatut::EnAttente => 'bg-yellow-100 text-yellow-800',
        MarcheStatut::EnRetard => 'bg-red-100 text-red-800',
        MarcheStatut::Termine => 'bg-blue-100 text-blue-800',
    };
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $classes }}">
    {{ $statutEnum->label() }}
</span>
