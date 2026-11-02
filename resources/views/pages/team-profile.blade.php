@extends('layouts.app')

@section('title', 'Professional profile | DhakaFin')
@section('meta_description', 'A professional profile page for team experience and qualifications.')
@section('meta_robots', 'noindex, follow')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Company" data-kind="profile" data-page="team-profile"@endsection

@section('json_ld')
@verbatim
<script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"https://dhakafin.com/"},{"@type":"ListItem","position":2,"name":"Professional profile","item":"https://dhakafin.com/pages/team-profile.html"}]}</script>
@endverbatim
@endsection

@section('content')
<section class="v2-hero v2-hero-light" data-area="Company"><div class="site-container v2-hero-inner"><div class="v2-hero-copy"><div class="v2-crumb"><a href="{{ route('home') }}">HOME</a><span>—</span><a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>PROFESSIONAL PROFILE</div><div class="v2-overline"><i></i>THE DHAKAFIN PERSPECTIVE <span>·</span> ADVISORY &amp; TAX SERVICES</div><h1>Professional profile.<br/><em>Designed for clarity.</em></h1><p>A professional profile page for team experience and qualifications.</p><div class="v2-hero-links"><a href="{{ route('pages.show', 'explore') }}">Explore DhakaFin <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewbox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a><a href="{{ route('pages.show', 'consultation') }}">Talk to our team <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewbox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a></div></div><div class="v2-visual v2-visual-company"><div class="v2-vis-head"><svg aria-hidden="true" class="ico" fill="none" height="26" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewbox="0 0 24 24" width="26"><circle cx="9" cy="8" r="3"></circle><path d="M2 20v-2a6 6 0 0 1 12 0v2M17 5a3 3 0 0 1 0 6m1 9v-2a5 5 0 0 0-2-4"></path></svg><span>DHAKAFIN / EDITORIAL</span><b>EST. 2026</b></div><div class="v2-vis-title">Human thinking.<br/><em>Modern tools.</em></div><div class="v2-company-symbol"><span>df.</span><span>FINANCIAL<br/>INTELLIGENCE</span></div><div class="v2-vis-bottom"><span>PRECISION &amp; PERSPECTIVE</span><span>FINANCIAL PERSPECTIVE</span></div></div></div><div class="site-container v2-hero-foot"><span>DHAKAFIN / THE DHAKAFIN PERSPECTIVE</span><span>SCROLL TO EXPLORE ↓</span></div></section>@php
    $teamList = $cms['team'] ?? [];
    $reqId = isset($request) ? (int) $request->query('id', 0) : (int) request('id', 0);
    $member = collect($teamList)->firstWhere('id', $reqId) ?? ($teamList[0] ?? [
        'code' => 'df.',
        'kicker' => 'PROFESSIONAL PROFILE / INFORMATION',
        'name' => 'Experience.',
        'bio' => "A structured space to present a real professional's qualifications, career experience, areas of practice and contact information after validation.",
        'tags' => ['Qualifications', 'Professional focus', 'Experience'],
    ]);
@endphp
<section class="content-section"><div class="site-container"><div class="profile-grid"><div class="profile-portrait">{{ $member['code'] ?? 'df.' }}<small>{{ $member['kicker'] ?? 'PROFILE PHOTO' }}</small></div><div><div class="section-kicker">{{ $member['kicker'] ?? 'PROFESSIONAL PROFILE' }}</div><h2 class="large-heading">{{ $member['name'] }}<br/><span class="serif">Context. Care.</span></h2><p class="big-copy">{{ $member['bio'] ?? $member['summary'] ?? '' }}</p><div class="chip-row">@foreach ($member['tags'] ?? ['Qualifications', 'Professional focus', 'Experience'] as $tag)<span class="pill">{{ $tag }}</span>@endforeach</div><a class="text-link" href="{{ route('pages.show', 'team') }}">← Back to team</a></div></div></div></section><section class="last-cta"><div class="site-container"><div><span class="section-kicker">DHAKAFIN / YOUR NEXT CHAPTER</span><h2>The next conversation<br/><em>starts here.</em></h2><p>Discover thoughtful financial guidance and connected tools for your next decision.</p></div><a class="light-btn" href="{{ route('pages.show', 'contact') }}">Start a conversation ↗</a></div></section>
@endsection
