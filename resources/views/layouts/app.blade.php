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
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2NCIgaGVpZ2h0PSI2NCIgdmlld0JveD0iMCAwIDY0IDY0Ij48cmVjdCB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHJ4PSIxNyIgZmlsbD0iIzA2NGUzYiIvPjxyZWN0IHg9IjUiIHk9IjUiIHdpZHRoPSI1NCIgaGVpZ2h0PSI1NCIgcng9IjE0IiBmaWxsPSIjYjllN2MzIi8+PHRleHQgeD0iMTEiIHk9IjQ1IiBmaWxsPSIjMDY0ZTNiIiBmb250LWZhbWlseT0iR2VvcmdpYSxzZXJpZiIgZm9udC1zaXplPSIzNiIgZm9udC13ZWlnaHQ9ImJvbGQiIGxldHRlci1zcGFjaW5nPSItMyI+ZGYuPC90ZXh0Pjwvc3ZnPg=="/>
    @yield('json_ld')
    @hasSection('simple_assets')
        @include('partials.styles-simple')
    @else
        @include('partials.styles-inner')
    @endif
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
        @include('partials.scripts-simple')
    @else
        @include('partials.scripts-inner')
    @endif
    @stack('scripts')
</body>
</html>
