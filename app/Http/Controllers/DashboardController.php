<?php

namespace App\Http\Controllers;

use App\Enums\MarcheStatut;
use App\Enums\EtapeStatut;
use App\Models\Marche;
use App\Models\Etape;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total' => Marche::count(),
            'en_cours' => Marche::where('statut', MarcheStatut::EnCours)->count(),
            'en_attente' => Marche::where('statut', MarcheStatut::EnAttente)->count(),
            'en_retard' => Marche::where('statut', MarcheStatut::EnRetard)->count(),
            'termine' => Marche::where('statut', MarcheStatut::Termine)->count(),
        ];

        // Steps stats (Completed vs Pending)
        $stepsCompleted = Etape::where('statut', EtapeStatut::Termine)->count();
        $stepsPending = Etape::whereIn('statut', [EtapeStatut::EnAttente, EtapeStatut::PasCommencee])->count();

        // Company distribution (Top 5)
        $companyDist = Marche::whereNotNull('entreprise')
            ->where('entreprise', '!=', '')
            ->groupBy('entreprise')
            ->selectRaw('entreprise, count(*) as count')
            ->orderByDesc('count')
            ->take(5)
            ->get()
            ->pluck('count', 'entreprise')
            ->toArray();

        $marchesRecents = Marche::with('etapes')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact('stats', 'stepsCompleted', 'stepsPending', 'companyDist', 'marchesRecents'));
    }
}
