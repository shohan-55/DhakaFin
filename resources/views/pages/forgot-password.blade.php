@extends('layouts.app')

@section('title', 'Reset password | DhakaFin')
@section('meta_description', 'Recover access to your DhakaFin account.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Account" data-kind="auth" data-page="forgot-password"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Account">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>
                RESET PASSWORD
            </div>
            <div class="v2-overline"><i></i>ACCOUNT RECOVERY <span>·</span> DHAKAFIN PLATFORM</div>
            <h1>Restore access<br/><em>to your account.</em></h1>
            <p>Enter your registered email address or update credentials directly in Access Settings.</p>
            <div class="v2-hero-links">
                <a href="{{ route('pages.show', 'login') }}">Return to sign in <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
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
        <div class="auth-layout">
            <div class="auth-aside">
                <div class="section-kicker">01 / ACCOUNT RECOVERY</div>
                <h2>Simple, secure<br/><span class="serif">password recovery.</span></h2>
                <p>If you need immediate assistance with your client account or administrative credentials, you can also manage access in Admin Studio.</p>
                <div class="auth-visual">
                    <span>DHAKAFIN / SECURITY</span>
                    <strong>Protected &amp;<br/>accessible.</strong>
                    <div>Need help? Contact {{ $cms['settings']['email'] ?? 'hello.sohan@outlook.com' }}.</div>
                </div>
            </div>
            <div class="auth-panel">
                <div class="section-kicker">PASSWORD RESET</div>
                <h2>Reset your password</h2>
                <p>Enter your account email and new password to update your credentials.</p>
                <form method="POST" action="{{ route('admin.auth.save') }}" class="demo-form">
                    @csrf
                    <label class="form-field">
                        <span>Account email address</span>
                        <input type="email" name="email" value="{{ $cms['admin_credentials']['email'] ?? 'admin@dhakafin.com' }}" required/>
                    </label>
                    <label class="form-field">
                        <span>New password</span>
                        <input type="password" name="password" required placeholder="Enter new password"/>
                        <button type="button" class="toggle-pass" data-password-toggle>Show</button>
                    </label>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">Update password <span>→</span></button>
                    </div>
                    @if (session('status'))
                        <p class="form-response">{{ session('status') }}</p>
                    @endif
                </form>
                <div class="auth-links">
                    <a href="{{ route('pages.show', 'login') }}">← Return to sign in</a>
                    <a href="{{ route('pages.show', 'verify-email') }}">Verify email</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
