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
                    <th class="px-6 py-3">Détails</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gris-clair">
                @foreach ($marches as $marche)
                    <tr class="hover:bg-dore-light/20">
                        <td class="px-6 py-4">
                            <a href="{{ route('marches.show', ['marche' => $marche->id]) }}" class="link-marine">{{ $marche->numero }}</a>
                        </td>
                        <td class="px-6 py-4">{{ $marche->objet }}</td>
                        <td class="px-6 py-4"><x-statut-badge :statut="$marche->statut" /></td>
                        <td class="px-6 py-4">
                            <a href="{{ route('marches.show', ['marche' => $marche->id]) }}" class="link-marine">Détails</a>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-2">

                                {{-- Bouton Modifier --}}
                                <a href="{{ route('marches.edit', ['marche' => $marche->id]) }}"
                                    title="Modifier"
                                    style="display:inline-flex; align-items:center; justify-content:center; height:32px; width:32px; border-radius:6px; background:#f59e0b; color:white; text-decoration:none;">
                                    <svg style="height:16px; width:16px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a2 2 0 01-1.414.586H7v-3a2 2 0 01.586-1.414z" />
                                    </svg>
                                </a>

                                {{-- Bouton Supprimer --}}
                                <form action="{{ route('marches.destroy', ['marche' => $marche->id]) }}" method="POST"
                                    style="display:inline-block; margin:0;"
                                    onsubmit="return confirm('Voulez-vous vraiment supprimer ce marché ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        title="Supprimer"
                                        style="display:inline-flex; align-items:center; justify-content:center; height:32px; width:32px; border-radius:6px; background:#ef4444; color:white; border:none; cursor:pointer;">
                                        <svg style="height:16px; width:16px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-6 0h6" />
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $marches->links() }}</div>
    </div>
@endsection