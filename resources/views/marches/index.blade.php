@extends('layouts.app')

@section('title', __('app.nav.marches'))
@section('page-title', __('app.marches'))

@section('header-actions')
    <a href="{{ route('marches.create') }}" class="btn-primary">{{ __('app.dashboard.add_marche') }}</a>
@endsection

@section('content')
    <div class="card-panel-lg overflow-hidden">
        <table class="w-full text-left text-sm text-gris-fonce">
            <thead class="table-head">
                <tr>
                    <th class="px-6 py-3">N°</th>
                    <th class="px-6 py-3">Objet</th>
                    <th class="px-6 py-3">Statut</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gris-clair">
                @foreach ($marches as $marche)
                    <tr class="hover:bg-dore-light/20">
                        <td class="px-6 py-4"><a href="{{ route('marches.show', $marche) }}" class="link-marine">{{ $marche->numero }}</a></td>
                        <td class="px-6 py-4">{{ $marche->objet }}</td>
                        <td class="px-6 py-4"><x-statut-badge :statut="$marche->statut" /></td>
                        <td class="px-6 py-4 text-right"><a href="{{ route('marches.show', $marche) }}" class="link-marine">Détails</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $marches->links() }}</div>
    </div>
@endsection
