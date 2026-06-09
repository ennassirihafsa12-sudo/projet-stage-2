@extends('layouts.app')

@section('title', 'Ajouter un marché')
@section('page-title', 'Ajouter un Marché')

@section('content')
    <form action="{{ route('marches.store') }}" method="POST" class="card-panel max-w-4xl p-8">
        @csrf
        @include('marches._form')

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('dashboard') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
@endsection
