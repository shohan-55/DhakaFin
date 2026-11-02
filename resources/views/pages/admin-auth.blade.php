@extends('layouts.app')

@section('title', 'Access settings | DhakaFin')
@section('meta_description', 'Administrative security and access settings for DhakaFin.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Admin Studio" data-kind="admin_form" data-page="admin-auth"@endsection

@section('content')
@php
    $creds = $cms['admin_credentials'] ?? [];
@endphp
<section class="v2-hero v2-hero-compact" data-area="Admin Studio">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'admin-dashboard') }}">ADMIN STUDIO</a><span>—</span>
                ACCESS SETTINGS
            </div>
            <div class="v2-overline"><i></i>100% NO-CODE WEBSITE CONTROL <span>·</span> ADMIN STUDIO</div>
            <h1>Administrative &amp;<br/><em>portal security.</em></h1>
            <p>Manage administrator login credentials and default client workspace profile details.</p>
            <div class="v2-hero-links">
                <a href="{{ route('home') }}?edit=1">Launch Live On-Page Editor <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
                <a href="{{ route('home') }}">View Live Website <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
            </div>
        </div>
        <div class="v2-visual v2-visual-tool">
            <div class="v2-vis-head">
                <span>DHAKAFIN / ADMIN STUDIO</span>
                <b>CMS ACTIVE</b>
            </div>
            <div class="v2-vis-title">Zero coding.<br/><em>Total control.</em></div>
            <div class="v2-console-track"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
            <div class="v2-vis-bottom"><span>INSTANT LIVE PUBLISHING</span><span>DHAKAFIN CMS</span></div>
        </div>
    </div>
</section>
<section class="content-section portal-section">
    <div class="site-container portal-layout">
        @include('partials.admin-sidebar')
        <div class="portal-main">
            @if (session('status'))
                <div class="preview-note"><span>✓</span><span>{{ session('status') }}</span></div>
            @endif
            <div class="portal-head">
                <div>
                    <div class="section-kicker">ADMIN STUDIO / ACCESS SETTINGS</div>
                    <h2>Security &amp; administrator credentials</h2>
                    <p>Configure the administrator email, password, and display name.</p>
                </div>
            </div>
            <div class="dash-surface">
                <form method="POST" action="{{ route('admin.auth.save') }}" class="panel-form demo-form">
                    @csrf
                    <div class="form-grid">
                        <label class="form-field">
                            <span>Administrator Name</span>
                            <input type="text" name="name" value="{{ $creds['name'] ?? 'DhakaFin Administrator' }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Administrator Email</span>
                            <input type="email" name="email" value="{{ $creds['email'] ?? 'admin@dhakafin.com' }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Administrator Password</span>
                            <input type="text" name="password" value="{{ $creds['password'] ?? 'dhakafin2026' }}" required/>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">Update Access Credentials <span>→</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
