<?php

namespace App\Http\Controllers;

use App\Models\Etape;
use App\Models\Marche;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EtapeController extends Controller
{
    public function index(): View
    {
        $marches = Marche::with('etapes')->latest()->get();

        return view('etapes.index', compact('marches'));
    }

    public function show(Marche $marche): View
    {
        $marche->load('etapes');

        return view('etapes.show', compact('marche'));
    }

    public function terminer(Etape $etape): RedirectResponse
    {
        if ($etape->statut->value !== 'en_attente') {
            return back()->with('error', 'Seule l\'étape en cours peut être terminée.');
        }

        $etape->terminer();

        return back()->with('success', 'Étape marquée comme terminée.');
    }
}
