@extends('layouts.app')

@section('title', __('app.lettres.title'))
@section('page-title', __('app.lettres.page_title'))

@section('content')
@php
    $isRtl = app()->getLocale() === 'ar';
@endphp
<div class="grid gap-8 lg:grid-cols-2" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <div class="card-panel-lg p-6">
        <h3 class="section-title mb-4 text-lg">{{ __('app.lettres.page_title') }}</h3>
        <form action="{{ route('lettres.store') }}" method="POST" id="lettre-form">
            @csrf
            <div id="header-hidden-fields"></div>
            <div class="space-y-4">
                <div>
                    <label for="marche_id" class="label-field">{{ __('app.marche') }}</label>
                    <select name="marche_id" id="marche_id" required class="input-field"
                            onchange="refreshLettrePage()">
                        <option value="">{{ __('app.lettres.select_marche') }}</option>
                        @foreach ($marches as $m)
                            <option value="{{ $m->id }}" @selected($marche?->id === $m->id)>
                                {{ $m->numero }} — {{ Str::limit($m->objet, 40) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="type" class="label-field">{{ __('app.lettres.type') }}</label>
                    <select name="type" id="type" class="input-field" onchange="refreshLettrePage()">
                        @foreach (\App\Enums\LettreType::cases() as $t)
                            <option value="{{ $t->value }}" @selected($type === $t)>{{ $t->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="entreprise" class="label-field">{{ __('app.lettres.entreprise') }}</label>
                    <input type="text" name="entreprise" id="entreprise" data-sync-field="entreprise"
                           value="{{ old('entreprise', $entreprise) }}" required
                           class="input-field"
                           oninput="syncEntreprisePreview(this.value)">
                </div>
                <div>
                    <label for="header_date" class="label-field">Date du document</label>
                    <input type="text" id="header_date" name="header_date"
                           value="{{ $header['date'] ?? now()->format('Y-m-d') }}" required
                           class="input-field" data-sync-field="header_date">
                </div>
                <div>
                    <label for="header_meeting_time" class="label-field">Heure de la commission</label>
                    <input type="text" id="header_meeting_time" name="header_meeting_time"
                           value="{{ $header['meeting_time'] ?? '10:00' }}" required
                           class="input-field" data-sync-field="header_meeting_time">
                </div>
                <div>
                    <label for="header_subject" class="label-field">Sujet / Objet</label>
                    <input type="text" id="header_subject" name="header_subject"
                           value="{{ $header['subject'] ?? ($marche ? $marche->objet : '') }}" required
                           class="input-field" data-sync-field="header_subject">
                </div>
                <div>
                    <label for="format" class="label-field">{{ __('app.lettres.format') }}</label>
                    <select name="format" class="input-field">
                        <option value="pdf" selected>PDF</option>
                    </select>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 mt-6">
                <button type="submit" class="btn-secondary flex-1 py-3 bg-marine hover:bg-marine-light text-white font-semibold rounded-2xl flex items-center justify-center gap-2">
                    {{ __('app.lettres.generate') }}
                </button>
                @if ($marche)
                    <button type="submit" id="btn-download-pdf" formaction="{{ route('lettres.download-pdf') }}" class="btn-primary flex-1 py-3 bg-dore text-marine hover:bg-dore-hover font-semibold rounded-2xl flex items-center justify-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Télécharger en PDF
                    </button>
                @endif
            </div>
        </form>
    </div>

    <div dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <h3 class="section-title mb-4 text-lg">{{ __('app.lettres.preview') }}</h3>
        @if ($marche)
            <div class="max-h-[75vh] overflow-y-auto rounded-2xl border border-gris-clair bg-gris-clair/30 p-4 shadow-inner">
                <x-lettre-document
                    :marche="$marche"
                    :type="$type"
                    :entreprise="$entreprise ?: ($marche->entreprise ?? 'Entreprise')"
                    :header="$header"
                    prefix="main"
                    id="lettre-preview-main" />
            </div>
            @if ($type === \App\Enums\LettreType::Acceptation)
                <div class="mt-4 max-h-[50vh] overflow-y-auto rounded-2xl border border-gris-clair bg-gris-clair/20 p-4 opacity-95">
                    <p class="mb-2 text-xs font-medium text-gris-moyen">{{ __('app.lettres.preview_refus') }}</p>
                    <x-lettre-document
                        :marche="$marche"
                        :type="\App\Enums\LettreType::Refus"
                        :entreprise="$entreprise ?: ($marche->entreprise ?? 'Entreprise')"
                        :header="$header"
                        prefix="refus"
                        id="lettre-preview-refus" />
                </div>
            @endif
        @else
            <p class="rounded-2xl border border-dashed border-gris-clair bg-white p-10 text-center text-gris-moyen shadow-sm">
                {{ __('app.lettres.preview_hint') }}
            </p>
        @endif
    </div>
</div>

@push('scripts')
<script>
(function () {
    const form = document.getElementById('lettre-form');
    const hiddenContainer = document.getElementById('header-hidden-fields');

    function syncHiddenFields() {
        if (!hiddenContainer) return;
        hiddenContainer.innerHTML = '';
        document.querySelectorAll('[data-sync-field]').forEach(function (input) {
            const name = input.getAttribute('data-sync-field');
            if (!name) return;
            
            // If the field is already present as a visible input in the form, do not duplicate it
            if (form.querySelector('[name="' + name + '"]:not([type="hidden"])')) {
                return;
            }
            if (form.querySelector('[name="header_' + name + '"]:not([type="hidden"])')) {
                return;
            }

            const main = document.querySelector('[name="main_' + name + '"]');
            const value = main ? main.value : input.value;
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = name;
            hidden.value = value;
            hiddenContainer.appendChild(hidden);
        });
    }

    function mirrorPreviewInputs() {
        const fields = {};
        document.querySelectorAll('[data-sync-field]').forEach(function (input) {
            const key = input.getAttribute('data-sync-field');
            if (!fields[key]) {
                fields[key] = input;
            }
            input.addEventListener('input', function () {
                const value = this.value;
                document.querySelectorAll('[data-sync-field="' + key + '"]').forEach(function (el) {
                    if (el !== input) el.value = value;
                });
                syncHiddenFields();
            });
        });
        syncHiddenFields();
    }

    window.syncEntreprisePreview = function (value) {
        document.querySelectorAll('[data-preview-entreprise]').forEach(function (el) {
            el.textContent = value || '—';
        });
    };

    window.refreshLettrePage = function () {
        const marcheId = document.getElementById('marche_id')?.value;
        if (!marcheId) return;
        const params = new URLSearchParams();
        params.set('marche_id', marcheId);
        const type = document.getElementById('type')?.value;
        const entreprise = document.getElementById('entreprise')?.value;
        if (type) params.set('type', type);
        if (entreprise) params.set('entreprise', entreprise);
        
        // Save current dynamic values
        document.querySelectorAll('[data-sync-field]').forEach(function (input) {
            const field = input.getAttribute('data-sync-field');
            if (field && input.value) {
                // Ensure correct parameter format (e.g. header_date, header_meeting_time, header_subject)
                const paramName = field.startsWith('header_') ? field : 'header_' + field;
                params.set(paramName, input.value);
            }
        });
        window.location.href = '{{ route('lettres.index') }}?' + params.toString();
    };

    if (form) {
        form.addEventListener('submit', syncHiddenFields);
    }

    mirrorPreviewInputs();
    syncEntreprisePreview(document.getElementById('entreprise')?.value || '');
})();
</script>
@endpush
@endsection
