<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DhakaFin — Tax, Audit, VAT & Advisory in Bangladesh')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('assets/site.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/grouped-nav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/motion.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/signature.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/production.css') }}">
    @stack('styles')
</head>
<body>
    @php
        $indexHtml = file_exists(base_path('index.html')) ? file_get_contents(base_path('index.html')) : '';
        $headerHtml = '';
        $footerHtml = '';
        if (preg_match('/<header\b[^>]*>.*?<\/header>/is', $indexHtml, $hm)) {
            $headerHtml = $hm[0];
        }
        if (preg_match('/<footer\b[^>]*>.*?<\/footer>/is', $indexHtml, $fm)) {
            $footerHtml = $fm[0];
        }
    @endphp

    {!! $headerHtml !!}

    <main id="main-content">
        @yield('content')
    </main>

    {!! $footerHtml !!}

    <script src="{{ asset('assets/site.js') }}" defer></script>
    <script src="{{ asset('assets/grouped-nav.js') }}" defer></script>
    <script src="{{ asset('assets/motion.js') }}" defer></script>
    <script src="{{ asset('assets/signature.js') }}" defer></script>
    <script src="{{ asset('assets/production.js') }}" defer></script>
    @stack('scripts')
</body>
        </html>
