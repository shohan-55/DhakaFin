@extends('layouts.app')

@section('title', 'Sign in | DhakaFin')
@section('meta_description', 'Sign in to your DhakaFin Client Workspace or Admin Studio.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Account" data-kind="auth" data-page="login"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Account">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>
                SIGN IN
            </div>
            <div class="v2-overline"><i></i>CLIENT & ADMIN ACCESS <span>·</span> DHAKAFIN PLATFORM</div>
            <h1>Welcome back<br/><em>to clarity.</em></h1>
            <p>Sign in to access the DhakaFin Client Workspace or the No-Code Admin Studio.</p>
            <div class="v2-hero-links">
                <a href="{{ route('pages.show', 'admin-dashboard') }}">Open Admin Studio <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
                <a href="{{ route('home') }}?edit=1">Live On-Page Visual Editor <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
            </div>
        </div>
        <div class="v2-visual v2-visual-tool">
            <div class="v2-vis-head">
                <span>DHAKAFIN / ACCOUNT</span>
                <b>LIVE SYSTEM</b>
            </div>
            <div class="v2-vis-title">Human thinking.<br/><em>Modern control.</em></div>
            <div class="v2-console-track"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
            <div class="v2-vis-bottom"><span>100% NO-CODE MANAGEMENT</span><span>REAL-TIME SYNC</span></div>
        </div>
    </div>
</section>
<section class="content-section">
    <div class="site-container">
        @if (session('status'))
            <div class="preview-note"><span>✓</span><span>{{ session('status') }}</span></div>
        @endif
        <div class="auth-layout">
            <div class="auth-aside">
                <div class="section-kicker">01 / SECURE WORKSPACE</div>
                <h2>One account.<br/><span class="serif">Every workflow.</span></h2>
                <p>Access your tax returns, Mushak 6.3 invoices, document vault, or manage the entire DhakaFin website from the Admin Studio without coding.</p>
                <div class="auth-visual">
                    <span>DHAKAFIN / ACCESS CREDENTIALS</span>
                    <strong>Admin Studio &amp;<br/>Client Portal</strong>
                    <div>Default Admin: <b>{{ $cms['admin_credentials']['email'] ?? 'admin@dhakafin.com' }}</b> (Password: <b>{{ $cms['admin_credentials']['password'] ?? 'dhakafin2026' }}</b>) · Or sign in with any client email.</div>
                </div>
            </div>
            <div class="auth-panel">
                <div class="section-kicker">SIGN IN</div>
                <h2>Access your workspace</h2>
                <p>Enter your email address and password to continue to your dashboard.</p>
                <form method="POST" action="{{ route('portal.login') }}" class="demo-form">
                    @csrf
                    <label class="form-field">
                        <span>Email address</span>
                        <input type="email" name="email" value="{{ $cms['admin_credentials']['email'] ?? 'admin@dhakafin.com' }}" required placeholder="admin@dhakafin.com"/>
                    </label>
                    <label class="form-field">
                        <span>Password</span>
                        <input type="password" name="password" value="{{ $cms['admin_credentials']['password'] ?? 'dhakafin2026' }}" required placeholder="Enter password"/>
                        <button type="button" class="toggle-pass" data-password-toggle>Show</button>
                    </label>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">Sign in to workspace <span>→</span></button>
                        <a href="{{ route('pages.show', 'admin-dashboard') }}" class="secondary-light">Direct Admin Studio ↗</a>
                    </div>
                </form>
                <div class="auth-links">
                    <a href="{{ route('pages.show', 'register') }}">Create an account ↗</a>
                    <a href="{{ route('pages.show', 'forgot-password') }}">Forgot password?</a>
                    <a href="{{ route('pages.show', 'client-dashboard') }}">Client Workspace ↗</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
