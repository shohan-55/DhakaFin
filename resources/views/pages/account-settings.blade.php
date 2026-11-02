@extends('layouts.app')

@section('title', 'Account settings | DhakaFin')
@section('meta_description', 'Manage your DhakaFin Client Workspace profile and preferences.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Client Workspace" data-kind="settings" data-page="account-settings"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Client Workspace">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>
                ACCOUNT SETTINGS
            </div>
            <div class="v2-overline"><i></i>PROFILE & PREFERENCES <span>·</span> DHAKAFIN PLATFORM</div>
            <h1>Manage your<br/><em>workspace profile.</em></h1>
            <p>Update your client name, organisation details, and notification email.</p>
            <div class="v2-hero-links">
                <a href="{{ route('pages.show', 'client-dashboard') }}">Client Overview <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
                <a href="{{ route('home') }}?edit=1">Live On-Page Visual Editor <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
            </div>
        </div>
        <div class="v2-visual v2-visual-tool">
            <div class="v2-vis-head">
                <span>DHAKAFIN / CLIENT WORKSPACE</span>
                <b>LIVE SYSTEM</b>
            </div>
            <div class="v2-vis-title">Human thinking.<br/><em>Modern control.</em></div>
            <div class="v2-console-track"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
            <div class="v2-vis-bottom"><span>100% NO-CODE MANAGEMENT</span><span>REAL-TIME SYNC</span></div>
        </div>
    </div>
</section>
<section class="content-section portal-section">
    <div class="site-container portal-layout">
        @include('partials.client-sidebar')
        <div class="portal-main">
            @if (session('status'))
                <div class="preview-note"><span>✓</span><span>{{ session('status') }}</span></div>
            @endif
            <div class="portal-head">
                <div>
                    <div class="section-kicker">CLIENT WORKSPACE / ACCOUNT SETTINGS</div>
                    <h2>Account settings</h2>
                    <p>Update your personal or corporate profile information displayed across the Client Workspace.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.auth.save') }}" class="panel-form demo-form">
                @csrf
                <div class="form-grid">
                    <label class="form-field">
                        <span>Client display name</span>
                        <input type="text" name="client_name" value="{{ $cms['admin_credentials']['client_name'] ?? 'DhakaFin Client' }}" required/>
                    </label>
                    <label class="form-field">
                        <span>Client email address</span>
                        <input type="email" name="client_email" value="{{ $cms['admin_credentials']['client_email'] ?? 'client@dhakafin.com' }}" required/>
                    </label>
                    <label class="form-field">
                        <span>Organisation / company name</span>
                        <input type="text" name="client_org" value="{{ $cms['admin_credentials']['client_org'] ?? 'Apex Enterprise Ltd.' }}"/>
                    </label>
                </div>
                <div class="form-actions">
                    <button type="submit" class="primary-btn">Save Profile Settings <span>→</span></button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
