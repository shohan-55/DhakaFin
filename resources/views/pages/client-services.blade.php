@extends('layouts.app')

@section('title', 'Client Services & Support | DhakaFin')
@section('meta_description', 'Connect with DhakaFin for client support, accounting documents, taxation and advisory enquiries in Bangladesh.')
@section('meta_robots', 'index, follow, max-image-preview:large')
@section('body_class', 'df-client-service')
@section('simple_assets', true)
@section('custom_footer')
    @include('partials.simple-footer')
@endsection

@section('content')
<section class="df-client-head"><span>CLIENT RELATIONSHIPS / DHAKAFIN</span><h1>Close to your business.<br/><em>Here when you need us.</em></h1><p>For accounting records, tax documentation, advisory support and ongoing engagements, connect directly with our team. Secure online account services are being introduced in stages.</p><div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap"><a class="df-simple-cta" href="{{ route('pages.show', 'client-dashboard') }}">Open Client Workspace ↗</a><a class="df-simple-cta" href="{{ route('pages.show', 'admin-dashboard') }}">Open Admin Studio ↗</a><a class="df-simple-cta" href="{{ route('pages.show', 'contact') }}">Contact our team ↗</a></div></section><section class="df-client-tiles"><article><b>01</b><h2>Accounting &amp; reporting</h2><p>Share your requirements with our team and discuss reporting deliverables, reconciliations and timelines.</p><a href="{{ route('pages.show', 'accounting') }}">Explore accounting ↗</a></article><article><b>02</b><h2>Tax &amp; VAT assistance</h2><p>Discuss returns, supporting records and withholding obligations with our advisory team.</p><a href="{{ route('pages.show', 'corporate-tax-service') }}">View tax advisory ↗</a></article><article><b>03</b><h2>Client enquiries</h2><p>Request information about engagement arrangements or coordinate your next consultation.</p><a href="{{ route('pages.show', 'consultation') }}">Request a consultation ↗</a></article></section>
@endsection
