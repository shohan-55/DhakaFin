<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#033b30"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="app-url" content="{{ url('/') }}"/>
    <meta name="description" content="@yield('meta_description', 'Professional accounting, income tax, VAT compliance, financial reporting, audit support and virtual CFO advisory for Bangladesh businesses and individuals.')"/>
    <title>@yield('title', 'DhakaFin | Accounting, Tax, VAT & Financial Advisory in Bangladesh')</title>
    <link rel="stylesheet" href="{{ asset('assets/home.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/motion.css') }}"/>
    <meta name="robots" content="index, follow, max-image-preview:large"/>
    <link rel="canonical" href="{{ url('/') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:site_name" content="DhakaFin"/>
    <meta property="og:locale" content="en_BD"/>
    <meta property="og:title" content="@yield('title', 'DhakaFin | Accounting, Tax, VAT & Financial Advisory in Bangladesh')"/>
    <meta property="og:description" content="@yield('meta_description', 'Professional accounting, income tax, VAT compliance, financial reporting, audit support and virtual CFO advisory for Bangladesh businesses and individuals.')"/>
    <meta property="og:url" content="{{ url('/') }}"/>
    <meta property="og:image" content="{{ asset('assets/dhakafin-og.png') }}"/>
    <meta property="og:image:width" content="1200"/>
    <meta property="og:image:height" content="630"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="@yield('title', 'DhakaFin | Accounting, Tax, VAT & Financial Advisory in Bangladesh')"/>
    <meta name="twitter:description" content="@yield('meta_description', 'Professional accounting, income tax, VAT compliance, financial reporting, audit support and virtual CFO advisory for Bangladesh businesses and individuals.')"/>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/production.css') }}"/>
    @verbatim
    <script type="application/ld+json">{"@context":"https://schema.org","@type":"ProfessionalService","name":"DhakaFin","url":"https://dhakafin.com/","logo":"https://dhakafin.com/assets/dhakafin-og.png","description":"Professional accounting, income tax, VAT compliance, financial reporting, audit support and virtual CFO advisory for Bangladesh businesses and individuals.","email":"hello.sohan@outlook.com","telephone":"+8801865555767","areaServed":{"@type":"Country","name":"Bangladesh"},"availableLanguage":["en","bn"],"serviceType":["Accounting","Tax Advisory","VAT Compliance","Financial Reporting","Audit Support","Virtual CFO"]}</script>
    @endverbatim
    <link rel="stylesheet" href="{{ asset('assets/grouped-nav.css') }}"/>
    @stack('styles')
    <script>
        window.DHAKAFIN_RATES = @json($cms['tax_rates'] ?? []);
        window.DHAKAFIN_LIBRARY = @json($cms['library'] ?? []);
        window.DHAKAFIN_SLUG = @json($slug ?? 'home');
    </script>
</head>
<body>
    @include('partials.home-header')

    @yield('content')

    @include('partials.home-footer')
    @include('partials.home-dialogs')

    @stack('scripts')
    <script defer src="{{ asset('assets/motion.js') }}"></script>
    <script defer src="{{ asset('assets/production.js') }}"></script>
    <script src="{{ asset('assets/grouped-nav.js') }}"></script>
</body>
</html>
