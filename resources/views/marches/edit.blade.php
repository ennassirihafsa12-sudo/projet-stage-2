@extends('layouts.app')

@section('title', 'Modifier le marché')
@section('page-title', 'Modifier le marché '.$marche->numero)

@section('content')
    <form action="{{ route('marches.update', $marche) }}" method="POST" class="card-panel max-w-4xl p-8">
        @csrf
        @method('PUT')
        @include('marches._form', ['marche' => $marche])

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('marches.show', $marche) }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
@endsection
