@php $marche = $marche ?? null; @endphp

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label class="label-field">Numéro de marché</label>
        <input type="text" name="numero" value="{{ old('numero', $marche?->numero) }}" required
               class="input-field">
        @error('numero')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="label-field">Objet du marché</label>
        <input type="text" name="objet" value="{{ old('objet', $marche?->objet) }}" required
               class="input-field">
        @error('objet')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="label-field">Date de publication</label>
        <input type="date" name="date_publication" value="{{ old('date_publication', $marche?->date_publication?->format('Y-m-d')) }}" required
               class="input-field">
    </div>
    <div>
        <label class="label-field">Date ouverture des plis</label>
        <input type="date" name="date_ouverture_plis" value="{{ old('date_ouverture_plis', $marche?->date_ouverture_plis?->format('Y-m-d')) }}" required
               class="input-field">
    </div>
    <div>
        <label class="label-field">Validité de l'offre (jours)</label>
        <input type="number" name="validite_offre_jours" value="{{ old('validite_offre_jours', $marche?->validite_offre_jours ?? 90) }}" min="1" required
               class="input-field">
    </div>
    <div>
        <label class="label-field">Entreprise</label>
        <input type="text" name="entreprise" value="{{ old('entreprise', $marche?->entreprise) }}"
               class="input-field">
    </div>
    <div>
        <label class="label-field">Montant estimatif (FCFA)</label>
        <input type="number" name="montant_estimatif" value="{{ old('montant_estimatif', $marche?->montant_estimatif) }}" step="1" min="0"
               class="input-field"
               placeholder="125000000">
    </div>
    <div>
        <label class="label-field">Responsable</label>
        <input type="text" name="responsable" value="{{ old('responsable', $marche?->responsable) }}"
               class="input-field">
    </div>
    <div class="md:col-span-2">
        <label class="label-field">Description</label>
        <textarea name="description" rows="4"
                  class="input-field">{{ old('description', $marche?->description) }}</textarea>
    </div>
</div>
