@extends('layouts.app')

@section('title', 'Verify email | DhakaFin')
@section('meta_description', 'Email verification status for your DhakaFin account.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Account" data-kind="auth" data-page="verify-email"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Account">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>
                VERIFY EMAIL
            </div>
            <div class="v2-overline"><i></i>ACCOUNT VERIFICATION <span>·</span> DHAKAFIN PLATFORM</div>
            <h1>Confirm your<br/><em>email address.</em></h1>
            <p>Your DhakaFin workspace account is active and ready for use.</p>
            <div class="v2-hero-links">
                <a href="{{ route('pages.show', 'client-dashboard') }}">Open Client Workspace <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
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
                <div class="section-kicker">01 / VERIFICATION STATUS</div>
                <h2>Verified &amp;<br/><span class="serif">ready to go.</span></h2>
                <p>Email verification helps ensure that tax assessments, Mushak invoices, and compliance alerts reach the right recipient.</p>
                <div class="auth-visual">
                    <span>DHAKAFIN / VERIFIED STATUS</span>
                    <strong>Account Active</strong>
                    <div>Connected email: {{ session('df_user_email', $cms['admin_credentials']['client_email'] ?? 'client@dhakafin.com') }}</div>
                </div>
            </div>
            <div class="auth-panel">
                <div class="section-kicker">EMAIL CONFIRMATION</div>
                <h2>Your email is verified</h2>
                <p>You have full access to the DhakaFin Client Workspace, Digital Tax Calculators, and Admin Studio.</p>
                <div class="form-actions">
                    <a href="{{ route('pages.show', 'client-dashboard') }}" class="primary-btn">Go to Client Workspace <span>→</span></a>
                    <a href="{{ route('pages.show', 'admin-dashboard') }}" class="secondary-light">Go to Admin Studio ↗</a>
                </div>
                <div class="auth-links">
                    <a href="{{ route('pages.show', 'login') }}">← Switch account</a>
                    <a href="{{ route('pages.show', 'account-settings') }}">Account settings ↗</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
