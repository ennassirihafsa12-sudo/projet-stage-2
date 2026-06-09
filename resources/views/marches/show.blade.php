@extends('layouts.app')

@section('title', 'Marché '.$marche->numero)
@section('page-title', 'Détails du marché — '.$marche->numero)

@section('header-actions')
    <a href="{{ route('lettres.index', ['marche_id' => $marche->id]) }}" class="btn-primary">Générer lettre</a>
@endsection

@section('content')
    <div class="mb-8 rounded-xl bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-lg font-semibold">Informations générales</h2>
        <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div><dt class="text-xs text-slate-500">Objet</dt><dd class="font-medium">{{ $marche->objet }}</dd></div>
            <div><dt class="text-xs text-slate-500">Publication</dt><dd>{{ $marche->date_publication?->format('d/m/Y') ?? '—' }}</dd></div>
            <div><dt class="text-xs text-slate-500">Ouverture des plis</dt><dd>{{ $marche->date_ouverture_plis?->format('d/m/Y') ?? '—' }}</dd></div>
            <div><dt class="text-xs text-slate-500">Validité offre</dt><dd>{{ $marche->validite_offre_jours }} jours</dd></div>
            <div><dt class="text-xs text-slate-500">Entreprise</dt><dd>{{ $marche->entreprise ?? '—' }}</dd></div>
            <div><dt class="text-xs text-slate-500">Montant</dt><dd>{{ $marche->montantFormate() }}</dd></div>
            <div><dt class="text-xs text-slate-500">Responsable</dt><dd>{{ $marche->responsable ?? '—' }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-xs text-slate-500">Description</dt><dd>{{ $marche->description ?? '—' }}</dd></div>
            <div><dt class="text-xs text-slate-500">Statut</dt><dd><x-statut-badge :statut="$marche->statut" /></dd></div>
        </dl>
    </div>

    <div class="mb-8 rounded-xl bg-white p-6 shadow-sm">
        <h2 class="mb-6 text-lg font-semibold">Avancement des étapes</h2>
        <x-stepper :etapes="$marche->etapes" />
    </div>

    <div class="flex flex-wrap gap-3">
        @php $etapeActive = $marche->etapes->firstWhere('statut', \App\Enums\EtapeStatut::EnAttente); @endphp
        @if ($etapeActive)
            <form action="{{ route('etapes.terminer', $etapeActive) }}" method="POST">
                @csrf
                <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">Étape terminée</button>
            </form>
        @endif
        <a href="{{ route('etapes.show', ['marche' => $marche->id]) }}" class="btn-primary">Voir les délais</a>
        <a href="{{ route('marches.edit', ['marche' => $marche->id]) }}" class="btn-secondary">Modifier</a>
    </div>
@endsection
