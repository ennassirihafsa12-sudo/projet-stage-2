@php
    $locale = app()->getLocale();
    $isRtl = $locale === 'ar';
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('app.app_name'))</title>
    @if ($isRtl)
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</head>
<body class="bg-white text-gris-fonce antialiased">
    <div class="flex min-h-screen">
        @include('components.sidebar')

        <div class="flex flex-1 flex-col overflow-hidden">
            @include('components.navbar')

            <main class="flex-1 overflow-auto bg-white" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                <header class="border-b border-gris-clair bg-white px-8 py-5 shadow-sm shadow-slate-100/50">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-semibold text-dore">@yield('page-title')</h1>
                            @hasSection('page-subtitle')
                                <p class="mt-1 text-sm text-gris-moyen">@yield('page-subtitle')</p>
                            @endif
                        </div>
                        @yield('header-actions')
                    </div>
                </header>

                <div class="p-8">
                    @if (session('success'))
                        <div class="alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert-error">{{ session('error') }}</div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
