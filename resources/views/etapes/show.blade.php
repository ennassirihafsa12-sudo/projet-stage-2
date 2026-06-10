@extends('layouts.app')

@section('title', 'Étapes — '.$marche->numero)
@section('page-title', 'Suivi des étapes — '.$marche->numero)
@section('page-subtitle', $marche->objet)

@section('content')
    <div class="rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-6 py-3 font-medium">Étape</th>
                    <th class="px-6 py-3 font-medium">Date prévue</th>
                    <th class="px-6 py-3 font-medium">Date réelle</th>
                    <th class="px-6 py-3 font-medium">Statut</th>
                    <th class="px-6 py-3 font-medium">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($marche->etapes as $etape)
                    <tr>
                        <td class="px-6 py-4 font-medium">{{ $etape->nom->label() }}</td>
                        <td class="px-6 py-4">{{ $etape->date_prevue?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-6 py-4">{{ $etape->date_reelle?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-6 py-4"><x-etape-badge :statut="$etape->statut" /></td>
                        <td class="px-6 py-4">
                            @if ($etape->statut === \App\Enums\EtapeStatut::EnAttente)
                                <form action="{{ route('etapes.terminer', $etape) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700">Terminer</button>
                                </form>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
