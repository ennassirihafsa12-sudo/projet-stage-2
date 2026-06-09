@extends('layouts.app')

@section('title', 'Suivi des étapes')
@section('page-title', 'Suivi des Étapes')

@section('content')
    <p class="mb-6 text-slate-600">Sélectionnez un marché pour voir le détail des étapes.</p>
    <div class="grid gap-4 md:grid-cols-2">
        @foreach ($marches as $marche)
            <a href="{{ route('etapes.show', ['marche' => $marche->id]) }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-300">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-blue-600">{{ $marche->numero }}</span>
                    <x-statut-badge :statut="$marche->statut" />
                </div>
                <p class="mt-2 text-sm text-slate-600">{{ Str::limit($marche->objet, 60) }}</p>
                <p class="mt-2 text-xs text-slate-500">Étape : {{ $marche->etapeCouranteLabel() }}</p>
            </a>
        @endforeach
    </div>
@endsection
