@extends('layouts.app')

@section('title', 'Délais et rappels')
@section('page-title', 'Délais et Rappels')

@section('content')
    <div class="space-y-4">
        @forelse ($rappels as $rappel)
            <x-alert-card
                :titre="$rappel->titre"
                :temps="$rappel->tempsRelatif()"
                :type="$rappel->type"
                :message="$rappel->message"
                :marche="$rappel->marche"
            />
        @empty
            <p class="rounded-xl bg-white p-8 text-center text-slate-500 shadow-sm">Aucun rappel pour le moment.</p>
        @endforelse
    </div>
@endsection
