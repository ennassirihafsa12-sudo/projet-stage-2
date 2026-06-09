@php
    $nav = [
        ['route' => 'dashboard', 'label' => __('app.nav.dashboard'), 'icon' => 'dashboard', 'match' => ['dashboard']],
        ['route' => 'marches.index', 'label' => __('app.nav.marches'), 'icon' => 'marches', 'match' => ['marches.*']],
        ['route' => 'etapes.index', 'label' => __('app.nav.etapes'), 'icon' => 'etapes', 'match' => ['etapes.*']],
        ['route' => 'rappels.index', 'label' => __('app.nav.rappels'), 'icon' => 'rappels', 'match' => ['rappels.*']],
        ['route' => 'notifications.index', 'label' => __('app.nav.notifications'), 'icon' => 'notifications', 'match' => ['notifications.*']],
        ['route' => 'lettres.index', 'label' => __('app.nav.lettres'), 'icon' => 'lettres', 'match' => ['lettres.*']],
        ['route' => 'profile.show', 'label' => __('app.nav.profile'), 'icon' => 'settings', 'match' => ['profile.*']],
    ];
    $locale = app()->getLocale();
@endphp

<aside class="flex w-64 flex-shrink-0 flex-col bg-marine text-white/90 shadow-xl">
    <div class="border-b border-white/10 px-5 py-6">
        <div class="mx-auto w-fit rounded-2xl border-4 border-dore bg-white p-2 shadow-md shadow-slate-100/50">
            <img src="{{ asset('images/logo.png') }}" alt="{{ __('app.administration') }}" class="h-20 w-20 object-contain" onerror="this.src='{{ asset('images/default-avatar.svg') }}'">
        </div>
        <p class="mt-4 text-center text-xs font-medium uppercase tracking-wider text-dore-light">{{ __('app.administration') }}</p>
        <h2 class="mt-1 text-center text-base font-semibold text-white">{{ __('app.app_name') }}</h2>
    </div>

    <nav class="flex-1 space-y-1 px-3 py-4">
        @foreach ($nav as $item)
            @php
                $active = false;
                foreach ($item['match'] as $pattern) {
                    if (request()->routeIs($pattern)) {
                        $active = true;
                        break;
                    }
                }
            @endphp
            <a href="{{ route($item['route']) }}" class="{{ $active ? 'nav-active' : 'nav-item' }}">
                @include('components.icons.'.$item['icon'])
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="border-t border-white/10 px-6 py-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-dore/50 bg-marine-light px-3 py-2.5 text-sm font-medium text-dore shadow-sm transition hover:bg-dore hover:text-marine">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                {{ __('app.logout') }}
            </button>
        </form>
        <p class="mt-3 text-center text-xs text-white/50">{{ __('app.republic_footer') }}</p>
    </div>
</aside>
