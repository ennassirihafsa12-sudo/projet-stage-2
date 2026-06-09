@props(['etapes'])

<div class="overflow-x-auto">
    <div class="flex min-w-[600px] items-center justify-between">
        @foreach ($etapes as $index => $etape)
            @php
                $isDone = $etape->statut === \App\Enums\EtapeStatut::Termine;
                $isActive = $etape->statut === \App\Enums\EtapeStatut::EnAttente;
                $nodeClass = $isDone ? 'bg-green-500 text-white' : ($isActive ? 'bg-blue-600 text-white ring-4 ring-blue-100' : 'bg-slate-200 text-slate-500');
                $lineClass = $isDone ? 'bg-green-500' : 'bg-slate-200';
            @endphp

            <div class="flex flex-1 items-center {{ $loop->last ? '' : '' }}">
                <div class="flex flex-col items-center text-center">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-semibold {{ $nodeClass }}">
                        @if ($isDone)
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        @else
                            {{ $etape->ordre }}
                        @endif
                    </div>
                    <p class="mt-2 max-w-[120px] text-xs font-medium text-slate-700">{{ $etape->nom->label() }}</p>
                    @if ($etape->date_reelle || $etape->date_prevue)
                        <p class="mt-1 text-xs text-slate-400">
                            {{ ($etape->date_reelle ?? $etape->date_prevue)?->format('d/m/Y') }}
                        </p>
                    @endif
                </div>

                @if (! $loop->last)
                    <div class="mx-2 h-1 flex-1 rounded {{ $lineClass }}"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>
