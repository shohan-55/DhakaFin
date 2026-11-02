<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#063e32"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="app-url" content="{{ url('/') }}"/>
    <meta name="description" content="@yield('meta_description', 'DhakaFin — Accounting, Tax, VAT & Financial Advisory in Bangladesh')"/>
    <title>@yield('title', 'DhakaFin')</title>
    @hasSection('simple_assets')
    @else
    <link rel="stylesheet" href="{{ asset('assets/site.css') }}"/>
    <script defer src="{{ asset('assets/site.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/signature.css') }}"/>
    <script defer src="{{ asset('assets/signature.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/motion.css') }}"/>
    @endif
    <meta name="robots" content="@yield('meta_robots', 'index, follow, max-image-preview:large')"/>
    <link rel="canonical" href="{{ url()->current() }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:site_name" content="DhakaFin"/>
    <meta property="og:locale" content="en_BD"/>
    <meta property="og:title" content="@yield('title', 'DhakaFin')"/>
    <meta property="og:description" content="@yield('meta_description', 'DhakaFin — Accounting, Tax, VAT & Financial Advisory in Bangladesh')"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:image" content="{{ asset('assets/dhakafin-og.png') }}"/>
    <meta property="og:image:width" content="1200"/>
    <meta property="og:image:height" content="630"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="@yield('title', 'DhakaFin')"/>
    <meta name="twitter:description" content="@yield('meta_description', 'DhakaFin — Accounting, Tax, VAT & Financial Advisory in Bangladesh')"/>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/production.css') }}"/>
    @yield('json_ld')
    <link rel="stylesheet" href="{{ asset('assets/grouped-nav.css') }}"/>
    @stack('styles')
    <script>
        window.DHAKAFIN_RATES = @json($cms['tax_rates'] ?? []);
        window.DHAKAFIN_LIBRARY = @json($cms['library'] ?? []);
        window.DHAKAFIN_SLUG = @json($slug ?? 'home');
    </script>
</head>
<body class="@yield('body_class', 'v2-page')" @yield('body_attrs')>
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @hasSection('custom_footer')
        @yield('custom_footer')
    @else
        @include('partials.footer')
        <div aria-live="polite" class="toast" id="toast" role="status"></div>
    @endif

    @hasSection('simple_assets')
    @else
    <script defer src="{{ asset('assets/motion.js') }}"></script>
    @endif
    <script defer src="{{ asset('assets/production.js') }}"></script>
    <script src="{{ asset('assets/grouped-nav.js') }}"></script>
    @stack('scripts')
</body>
</html>
