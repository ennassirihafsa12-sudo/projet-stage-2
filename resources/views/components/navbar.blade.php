@php
    $user = auth()->user();
    $locale = app()->getLocale();
    $languages = [
        'fr' => ['label' => 'Français', 'flag' => 'FR'],
        'en' => ['label' => 'English', 'flag' => 'EN'],
        'ar' => ['label' => 'العربية', 'flag' => 'AR'],
    ];
@endphp

<header class="flex items-center justify-between border-b border-marine-dark bg-marine px-6 py-3 shadow-md">
    <p class="text-sm font-medium text-white/90">
        {{ __('app.welcome') }}, <span class="font-semibold text-dore">{{ $user->name }}</span>
    </p>

    <div class="flex items-center gap-4">
        @php
            $unreadNotificationsCount = once(fn () => \App\Models\Notification::where('lu', false)->count());
        @endphp
        <a href="{{ route('notifications.index') }}" 
           class="relative flex h-9 w-9 items-center justify-center rounded-xl border border-white/20 bg-marine-light text-white transition hover:border-dore hover:text-dore"
           title="{{ __('app.nav.notifications') }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            @if ($unreadNotificationsCount > 0)
                <span class="absolute -top-1.5 -right-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white ring-2 ring-marine animate-pulse">
                    {{ $unreadNotificationsCount }}
                </span>
            @endif
        </a>
        <details class="group relative">
            <summary class="flex cursor-pointer list-none items-center gap-2 rounded-xl border border-white/20 bg-marine-light px-3 py-2 text-sm text-white transition hover:border-dore hover:text-dore [&::-webkit-details-marker]:hidden">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
                {{ __('app.change_language') }}
                <span class="rounded bg-dore/20 px-1.5 py-0.5 text-xs font-bold text-dore">{{ strtoupper($locale) }}</span>
                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
            </summary>
            <div class="dropdown-panel {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-52">
                <p class="px-4 py-2 text-xs font-semibold uppercase tracking-wide text-dore/80">{{ __('app.language') }}</p>
                @foreach ($languages as $code => $lang)
                    <a href="{{ route('locale.switch', $code) }}"
                       class="{{ $locale === $code ? 'dropdown-item-active' : 'dropdown-item' }}">
                        <span class="text-base font-bold" aria-hidden="true">{{ $lang['flag'] }}</span>
                        {{ $lang['label'] }}
                    </a>
                @endforeach
            </div>
        </details>

        <details class="group relative">
            <summary class="flex cursor-pointer list-none items-center gap-3 rounded-xl border border-white/20 bg-marine-light px-2 py-1.5 pr-3 transition hover:border-dore [&::-webkit-details-marker]:hidden">
                <img src="{{ $user->profilePhotoUrl() }}"
                     alt="{{ $user->name }}"
                     class="h-9 w-9 rounded-full border-2 border-dore object-cover shadow-sm">
                <span class="hidden text-sm font-medium text-white sm:inline">{{ $user->name }}</span>
                <svg class="h-3 w-3 text-dore" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
            </summary>
            <div class="dropdown-panel {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-60">
                <div class="border-b border-white/10 px-4 py-3">
                    <p class="text-sm font-semibold text-white">{{ $user->name }}</p>
                    <p class="text-xs text-white/60">{{ $user->email }}</p>
                </div>
                <a href="{{ route('profile.show') }}" class="dropdown-item">
                    {{ __('app.nav.profile') }}
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item w-full text-red-300 hover:text-red-200">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        {{ __('app.logout') }}
                    </button>
                </form>
            </div>
        </details>
    </div>
</header>
