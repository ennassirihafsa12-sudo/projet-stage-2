@extends('layouts.app')

@section('title', __('app.profile.title'))
@section('page-title', __('app.profile.title'))
@section('page-subtitle', __('app.profile.subtitle'))

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="card-panel-lg overflow-hidden">
        <div class="border-b border-gris-clair bg-marine px-6 py-8 text-center">
            <div class="relative mx-auto h-28 w-28">
                <img src="{{ $user->profilePhotoUrl() }}"
                     alt="{{ $user->name }}"
                     class="h-28 w-28 rounded-full border-4 border-dore object-cover shadow-lg shadow-slate-100/50">
            </div>
            <h2 class="mt-4 text-xl font-semibold text-white">{{ $user->name }}</h2>
            <p class="text-sm text-dore-light">{{ $user->email }}</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert-error">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-8">
                <h3 class="section-title text-lg">{{ __('app.profile.photo') }}</h3>
                <p class="mt-1 text-sm text-gris-moyen">{{ __('app.profile.photo_hint') }}</p>

                <div class="mt-4 flex flex-wrap items-center gap-4">
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/jpeg,image/png,image/webp"
                           class="block w-full text-sm text-gris-moyen file:me-4 file:rounded-xl file:border-0 file:bg-dore file:px-4 file:py-2 file:text-sm file:font-semibold file:text-marine hover:file:bg-dore-hover">
                    @if ($user->profile_photo_path)
                        <button type="button"
                                class="text-sm font-medium text-red-600 hover:underline"
                                onclick="document.getElementById('delete-photo-form').submit();">
                            {{ __('app.profile.delete_photo') }}
                        </button>
                    @endif
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="name" class="label-field">{{ __('app.profile.name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="input-field">
                </div>

                <div class="md:col-span-2">
                    <label for="email" class="label-field">{{ __('app.auth.email') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="input-field">
                </div>
            </div>

            <hr class="my-8 border-gris-clair">

            <h3 class="section-title text-lg">{{ __('app.profile.password_section') }}</h3>
            <p class="mt-1 text-sm text-gris-moyen">{{ __('app.profile.password_hint') }}</p>

            <div class="mt-4 grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="current_password" class="label-field">{{ __('app.profile.current_password') }}</label>
                    <input type="password" name="current_password" id="current_password" class="input-field" autocomplete="current-password">
                </div>
                <div>
                    <label for="password" class="label-field">{{ __('app.profile.new_password') }}</label>
                    <input type="password" name="password" id="password" class="input-field" autocomplete="new-password">
                </div>
                <div>
                    <label for="password_confirmation" class="label-field">{{ __('app.profile.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="input-field" autocomplete="new-password">
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('dashboard') }}" class="btn-secondary">{{ __('app.profile.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('app.profile.save') }}</button>
            </div>
        </form>
    </div>
</div>

@if ($user->profile_photo_path)
<form id="delete-photo-form" action="{{ route('profile.photo.destroy') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endif
@endsection
