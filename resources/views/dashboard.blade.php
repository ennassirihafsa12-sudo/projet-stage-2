@extends('layouts.app')

@section('title', __('app.nav.dashboard'))
@section('page-title', __('app.dashboard.title'))

@section('header-actions')
    <a href="{{ route('marches.create') }}" class="btn-primary">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        {{ __('app.dashboard.add_marche') }}
    </a>
@endsection

@section('content')
    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-kpi-card :label="__('app.dashboard.total_marches')" :value="$stats['total']" color="marine" />
        <x-kpi-card :label="__('app.dashboard.en_cours')" :value="$stats['en_cours']" color="green" />
        <x-kpi-card :label="__('app.dashboard.en_attente')" :value="$stats['en_attente']" color="yellow" />
        <x-kpi-card :label="__('app.dashboard.en_retard')" :value="$stats['en_retard']" color="red" />
    </div>

    <!-- Charts Grid Section -->
    <div class="mb-8 grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        <!-- Chart 1: Market Status -->
        <div class="card-panel-lg p-6 bg-white border border-gris-clair flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-marine mb-1 text-center">Statut des Marchés</h3>
                <p class="text-xs text-gris-moyen text-center mb-4">Répartition globale des états de marchés</p>
            </div>
            <div class="mx-auto w-full max-w-[200px] h-[200px] relative">
                <canvas id="chartMarches"></canvas>
            </div>
        </div>

        <!-- Chart 2: Steps Progress -->
        <div class="card-panel-lg p-6 bg-white border border-gris-clair flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-marine mb-1 text-center">Avancement des Étapes</h3>
                <p class="text-xs text-gris-moyen text-center mb-4">Étapes complétées vs en attente</p>
            </div>
            <div class="mx-auto w-full max-w-[200px] h-[200px] relative">
                <canvas id="chartEtapes"></canvas>
            </div>
        </div>

        <!-- Chart 3: Company Distribution -->
        <div class="card-panel-lg p-6 bg-white border border-gris-clair flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-marine mb-1 text-center">Distribution par Entreprise</h3>
                <p class="text-xs text-gris-moyen text-center mb-4">Top 5 des entreprises attributaires</p>
            </div>
            <div class="mx-auto w-full h-[200px] relative">
                <canvas id="chartEntreprises"></canvas>
            </div>
        </div>
    </div>

    <div class="card-panel-lg overflow-hidden">
        <div class="border-b border-gris-clair px-6 py-4">
            <h2 class="section-title text-lg">{{ __('app.dashboard.recent_marches') }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-start text-sm text-gris-fonce">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-3 font-medium">{{ __('app.dashboard.numero') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('app.dashboard.objet') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('app.dashboard.etape') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('app.dashboard.temps_restant') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('app.dashboard.statut') }}</th>
                        <th class="px-6 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gris-clair">
                    @forelse ($marchesRecents as $marche)
                        <tr class="transition hover:bg-dore-light/30">
                            <td class="px-6 py-4 font-medium">
                                <a href="{{ route('marches.show', ['marche' => $marche->id]) }}" class="link-marine">{{ $marche->numero }}</a>
                            </td>
                            <td class="px-6 py-4">{{ Str::limit($marche->objet, 40) }}</td>
                            <td class="px-6 py-4">{{ $marche->etapeCouranteLabel() }}</td>
                            <td class="px-6 py-4">{{ $marche->tempsRestant() ?? '—' }}</td>
                            <td class="px-6 py-4"><x-statut-badge :statut="$marche->statut" /></td>
                            <td class="px-6 py-4 text-end">
                                <a href="{{ route('marches.show', ['marche' => $marche->id]) }}" class="link-marine">{{ __('app.dashboard.voir') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gris-moyen">
                                {{ __('app.dashboard.aucun') }}
                                <a href="{{ route('marches.create') }}" class="link-marine">{{ __('app.dashboard.ajouter') }}</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script type="module">
        const Chart = window.Chart;

        // Chart 1: Marches par Statut
        const statsMarches = {
            'En cours': {{ $stats['en_cours'] }},
            'En attente': {{ $stats['en_attente'] }},
            'En retard': {{ $stats['en_retard'] }},
            'Terminé': {{ $stats['termine'] }}
        };
        new Chart(document.getElementById('chartMarches'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(statsMarches),
                datasets: [{
                    data: Object.values(statsMarches),
                    backgroundColor: ['#22c55e', '#eab308', '#ef4444', '#3b82f6'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, font: { size: 11 } }
                    }
                }
            },
        });

        // Chart 2: Etapes Complétées vs En attente
        const statsEtapes = {
            'Complétées': {{ $stepsCompleted }},
            'En attente': {{ $stepsPending }}
        };
        new Chart(document.getElementById('chartEtapes'), {
            type: 'pie',
            data: {
                labels: Object.keys(statsEtapes),
                datasets: [{
                    data: Object.values(statsEtapes),
                    backgroundColor: ['#1a2744', '#cbd5e1'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, font: { size: 11 } }
                    }
                }
            },
        });

        // Chart 3: Distribution par Entreprise
        const companyData = @json($companyDist);
        new Chart(document.getElementById('chartEntreprises'), {
            type: 'bar',
            data: {
                labels: Object.keys(companyData),
                datasets: [{
                    label: 'Marchés',
                    data: Object.values(companyData),
                    backgroundColor: '#c9a227',
                    borderRadius: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { size: 10 } }
                    },
                    y: {
                        ticks: { font: { size: 10 } }
                    }
                },
                plugins: {
                    legend: { display: false }
                },
            },
        });
    </script>
    @endpush
@endsection
