@extends('layouts.auth')

@section('title', __('app.auth.login'))

@section('content')
<div class="flex min-h-screen">
    <aside class="hidden w-1/2 flex-col justify-between bg-marine-dark p-10 lg:flex xl:p-14">
        <div>
            <div class="mx-auto w-fit rounded-3xl border-4 border-dore bg-white p-4 shadow-xl shadow-slate-900/20">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('app.administration') }}" class="h-32 w-32 object-contain" onerror="this.style.display='none'">
            </div>
            <h1 class="mt-8 text-center text-2xl font-bold text-dore">{{ __('app.app_name') }}</h1>
            <p class="mt-2 text-center text-sm text-white/70">{{ __('app.administration') }}</p>
        </div>

        <div class="rounded-2xl border border-dore/40 bg-marine p-6 shadow-md shadow-slate-900/30">
            <h2 class="flex items-center gap-2 text-lg font-semibold text-dore">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ __('app.auth.admin_notices') }}
            </h2>
            <ul class="mt-4 space-y-3 text-sm leading-relaxed text-white/85">
                <li class="flex gap-2">
                    <span class="mt-1.5 h-1.5 w-1.5 flex-shrink-0 rounded-full bg-dore"></span>
                    L'accès est réservé aux agents habilités de l'administration. Toute connexion est journalisée.
                </li>
                <li class="flex gap-2">
                    <span class="mt-1.5 h-1.5 w-1.5 flex-shrink-0 rounded-full bg-dore"></span>
                    Respectez la confidentialité des dossiers de marchés publics conformément à la réglementation en vigueur.
                </li>
                <li class="flex gap-2">
                    <span class="mt-1.5 h-1.5 w-1.5 flex-shrink-0 rounded-full bg-dore"></span>
                    En cas d'oubli de mot de passe, contactez le service informatique de votre direction.
                </li>
                <li class="flex gap-2">
                    <span class="mt-1.5 h-1.5 w-1.5 flex-shrink-0 rounded-full bg-dore"></span>
                    Déconnectez-vous systématiquement après chaque session de travail sur un poste partagé.
                </li>
            </ul>
        </div>

        <p class="text-center text-xs text-white/40">&copy; {{ date('Y') }} — {{ __('app.administration') }}</p>
    </aside>

    <section class="flex w-full flex-col justify-center bg-white px-6 py-12 sm:px-12 lg:w-1/2">
        <div class="mx-auto w-full max-w-md">
            <div class="mb-8 text-center lg:hidden">
                <div class="mx-auto w-fit rounded-2xl border-4 border-dore bg-white p-3 shadow-md">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-20 object-contain">
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gris-fonce">{{ __('app.auth.login') }}</h2>
            <p class="mt-1 text-sm text-gris-moyen">{{ __('app.app_name') }}</p>

            @if (session('success'))
                <div class="alert-success mt-6">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert-error mt-6">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="email" class="label-field">{{ __('app.auth.email') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="input-field" placeholder="admin@example.com">
                </div>

                <div>
                    <label for="password" class="label-field">{{ __('app.auth.password') }}</label>
                    <input type="password" name="password" id="password" required
                           class="input-field" placeholder="••••••••">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" value="1"
                           class="h-4 w-4 rounded border-gris-clair text-dore focus:ring-dore"
                           @checked(old('remember'))>
                    <label for="remember" class="text-sm text-gris-moyen">{{ __('app.auth.remember') }}</label>
                </div>

                <button type="submit" class="btn-primary w-full py-3 text-base">
                    {{ __('app.auth.submit_login') }}
                </button>

                <a href="{{ route('home') }}" class="btn-secondary mt-4 inline-flex w-full items-center justify-center rounded-xl border border-marine bg-white px-4 py-3 text-base font-semibold text-marine shadow-sm hover:border-dore hover:text-dore">
                    {{ __('Accueil') }}
                </a>
            </form>

            <p class="mt-8 text-center text-sm text-gris-moyen">
                {{ __('app.auth.no_account') }}
                <a href="{{ route('register') }}" class="font-semibold text-marine hover:text-dore">{{ __('app.auth.create_account') }}</a>
            </p>
        </div>
    </section>
</div>
@endsection
