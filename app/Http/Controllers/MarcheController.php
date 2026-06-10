<?php

namespace App\Http\Controllers;

use App\Enums\MarcheStatut;
use App\Models\Marche;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarcheController extends Controller
{
    public function index(): View
    {
        $marches = Marche::with('etapes')->latest()->paginate(15);

        return view('marches.index', compact('marches'));
    }

    public function create(Request $request): View
    {
        $marche = new Marche($request->only([
            'numero',
            'objet',
            'date_publication',
            'date_ouverture_plis',
            'validite_offre_jours',
            'entreprise',
            'montant_estimatif',
            'responsable',
            'description',
        ]));

        return view('marches.create', compact('marche'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:50|unique:marches,numero',
            'objet' => 'required|string|max:255',
            'date_publication' => 'required|date',
            'date_ouverture_plis' => 'required|date|after_or_equal:date_publication',
            'validite_offre_jours' => 'required|integer|min:1|max:365',
            'entreprise' => 'nullable|string|max:255',
            'montant_estimatif' => 'nullable|numeric|min:0',
            'responsable' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
        ]);

        $marche = Marche::create([
            ...$validated,
            'statut' => MarcheStatut::EnCours,
        ]);

        Marche::creerEtapesParDefaut($marche);

        return redirect()
            ->route('marches.create')
            ->with('success', 'Marché créé avec succès.');
    }

    public function show(Marche $marche): View
    {
        $marche->load('etapes');

        return view('marches.show', compact('marche'));
    }

    public function edit(Marche $marche): View
    {
        return view('marches.edit', compact('marche'));
    }

    public function update(Request $request, Marche $marche): RedirectResponse
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:50|unique:marches,numero,'.$marche->id,
            'objet' => 'required|string|max:255',
            'date_publication' => 'required|date',
            'date_ouverture_plis' => 'required|date|after_or_equal:date_publication',
            'validite_offre_jours' => 'required|integer|min:1|max:365',
            'entreprise' => 'nullable|string|max:255',
            'montant_estimatif' => 'nullable|numeric|min:0',
            'responsable' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $marche->update($validated);
        $marche->recalculerStatut();
        $marche->genererRappels();

        return redirect()
            ->route('marches.show', $marche)
            ->with('success', 'Marché mis à jour.');
    }

    public function destroy(Marche $marche): RedirectResponse
    {
        $marche->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Marché supprimé.');
    }
}