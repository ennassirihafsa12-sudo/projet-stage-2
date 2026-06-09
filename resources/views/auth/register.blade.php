@extends('layouts.auth')

@section('title', __('app.auth.register'))

@section('content')
<div class="flex min-h-screen">
    <aside class="hidden w-2/5 flex-col justify-center bg-marine-dark p-10 lg:flex">
        <div class="mx-auto w-fit rounded-3xl border-4 border-dore bg-white p-4 shadow-xl">
            <img src="{{ asset('images/logo.png') }}" alt="{{ __('app.administration') }}" class="h-28 w-28 object-contain">
        </div>
        <h1 class="mt-8 text-center text-xl font-bold text-dore">{{ __('app.auth.register') }}</h1>
        <p class="mt-3 text-center text-sm leading-relaxed text-white/75">
            {{ __('app.administration') }}
        </p>
    </aside>

    <section class="flex w-full flex-col justify-center bg-white px-6 py-12 sm:px-12 lg:w-3/5">
        <div class="mx-auto w-full max-w-md">
            <div class="mb-6 text-center lg:hidden">
                <div class="mx-auto w-fit rounded-2xl border-4 border-dore bg-white p-2 shadow-md">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16 object-contain">
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gris-fonce">{{ __('app.auth.register') }}</h2>
            <p class="mt-1 text-sm text-gris-moyen">{{ __('app.app_name') }}</p>

            @if ($errors->any())
                <div class="alert-error mt-6">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="name" class="label-field">{{ __('app.profile.name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="input-field">
                </div>

                <div>
                    <label for="email" class="label-field">{{ __('app.auth.email') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="input-field">
                </div>

                <div>
                    <label for="password" class="label-field">{{ __('app.auth.password') }}</label>
                    <input type="password" name="password" id="password" required minlength="8" class="input-field">
                </div>

                <div>
                    <label for="password_confirmation" class="label-field">{{ __('app.profile.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="input-field">
                </div>

                <button type="submit" class="btn-primary w-full py-3 text-base">
                    {{ __('app.auth.submit_register') }}
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-gris-moyen">
                {{ __('app.auth.has_account') }}
                <a href="{{ route('login') }}" class="font-semibold text-marine hover:text-dore">{{ __('app.auth.sign_in') }}</a>
            </p>
        </div>
    </section>
</div>
@endsection
