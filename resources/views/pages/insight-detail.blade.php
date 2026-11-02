@extends('layouts.app')

@section('title', 'Article detail | DhakaFin')
@section('meta_description', 'A polished editorial reading experience for DhakaFin publications.')
@section('meta_robots', 'noindex, follow')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Company" data-kind="article" data-page="insight-detail"@endsection

@section('json_ld')
@verbatim
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"https://dhakafin.com/"},{"@type":"ListItem","position":2,"name":"Article detail","item":"https://dhakafin.com/pages/insight-detail.html"}]}</script>
@endverbatim
@endsection

@section('content')
<section class="v2-hero v2-hero-light" data-area="Company"><div class="site-container v2-hero-inner"><div class="v2-hero-copy"><div class="v2-crumb"><a href="{{ route('home') }}">HOME</a><span>—</span><a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>ARTICLE DETAIL</div><div class="v2-overline"><i></i>THE DHAKAFIN PERSPECTIVE <span>·</span> ADVISORY &amp; TAX SERVICES</div><h1>Article detail.<br/><em>Designed for clarity.</em></h1><p>A polished editorial reading experience for DhakaFin publications.</p><div class="v2-hero-links"><a href="{{ route('pages.show', 'explore') }}">Explore DhakaFin <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewbox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a><a href="{{ route('pages.show', 'consultation') }}">Talk to our team <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewbox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a></div></div><div class="v2-visual v2-visual-company"><div class="v2-vis-head"><svg aria-hidden="true" class="ico" fill="none" height="26" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewbox="0 0 24 24" width="26"><circle cx="9" cy="8" r="3"></circle><path d="M2 20v-2a6 6 0 0 1 12 0v2M17 5a3 3 0 0 1 0 6m1 9v-2a5 5 0 0 0-2-4"></path></svg><span>DHAKAFIN / EDITORIAL</span><b>EST. 2026</b></div><div class="v2-vis-title">Human thinking.<br/><em>Modern tools.</em></div><div class="v2-company-symbol"><span>df.</span><span>FINANCIAL<br/>INTELLIGENCE</span></div><div class="v2-vis-bottom"><span>PRECISION &amp; PERSPECTIVE</span><span>FINANCIAL PERSPECTIVE</span></div></div></div><div class="site-container v2-hero-foot"><span>DHAKAFIN / THE DHAKAFIN PERSPECTIVE</span><span>SCROLL TO EXPLORE ↓</span></div></section>@php
    $articles = $cms['articles'] ?? [];
    $reqId = isset($request) ? (int) $request->query('id', 0) : (int) request('id', 0);
    $art = collect($articles)->firstWhere('id', $reqId) ?? ($articles[0] ?? [
        'category' => 'INSIGHTS / EDITORIAL OVERVIEW',
        'title' => 'Better financial decisions begin with better questions.',
        'subtitle' => 'DhakaFin editorial layout · information reading experience',
        'body_p1' => 'Financial information becomes more valuable when it is organised around real business questions. A clear reporting framework can help people see connections and communicate decisions more confidently.',
        'body_h1' => 'Clarity before complexity',
        'body_p2' => 'Start with the underlying facts. Separate assumptions from confirmed figures. Build a record of what has been reviewed and what still requires judgement.',
        'body_h2' => 'Thoughtful next steps',
        'body_p3' => 'This information article illustrates a premium publishing experience. All topic-specific guidance and legal claims will be reviewed before publication.',
    ]);
@endphp
<section class="content-section"><div class="site-container article-wrap"><div class="section-kicker">{{ $art['category'] ?? 'INSIGHTS / EDITORIAL OVERVIEW' }}</div><h2>{{ $art['title'] }}</h2><p class="article-sub">{{ $art['subtitle'] ?? 'DhakaFin editorial layout · information reading experience' }}</p><div class="article-feature">df. <span>FINANCIAL THINKING / DHAKAFIN INSIGHTS</span></div><div class="prose"><p>{{ $art['body_p1'] }}</p><h3>{{ $art['body_h1'] }}</h3><p>{{ $art['body_p2'] }}</p><h3>{{ $art['body_h2'] }}</h3><p>{{ $art['body_p3'] }}</p><a class="text-link" href="{{ route('pages.show', 'insights') }}">← Return to insights</a></div></div></section><section class="last-cta"><div class="site-container"><div><span class="section-kicker">DHAKAFIN / YOUR NEXT CHAPTER</span><h2>The next conversation<br/><em>starts here.</em></h2><p>Discover thoughtful financial guidance and connected tools for your next decision.</p></div><a class="light-btn" href="{{ route('pages.show', 'contact') }}">Start a conversation ↗</a></div></section>
@endsection
