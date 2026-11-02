@extends('layouts.app')

@section('title', 'Create an account | DhakaFin')
@section('meta_description', 'Onboarding for DhakaFin clients and businesses.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Account" data-kind="auth" data-page="register"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Account">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>
                CREATE AN ACCOUNT
            </div>
            <div class="v2-overline"><i></i>CLIENT ONBOARDING <span>·</span> DHAKAFIN PLATFORM</div>
            <h1>Begin your journey<br/><em>with DhakaFin.</em></h1>
            <p>Create your client profile to organise tax returns, Mushak records, and advisory documents.</p>
            <div class="v2-hero-links">
                <a href="{{ route('pages.show', 'login') }}">Already have an account? <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
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
                <div class="section-kicker">01 / CLIENT ONBOARDING</div>
                <h2>Designed for<br/><span class="serif">individuals &amp; teams.</span></h2>
                <p>Keep your tax assessments, VAT returns, Mushak 6.3 invoices, and advisory records organised in one connected workspace.</p>
                <div class="auth-visual">
                    <span>DHAKAFIN / CLIENT WORKSPACE</span>
                    <strong>Clarity from<br/>day one.</strong>
                    <div>Instant access to Personal &amp; Corporate Tax Calculators, Mushak 6.3 Creator, and Document Vault.</div>
                </div>
            </div>
            <div class="auth-panel">
                <div class="section-kicker">REGISTER</div>
                <h2>Create your account</h2>
                <p>Complete your details below to open your DhakaFin Client Workspace.</p>
                <form method="POST" action="{{ route('portal.register') }}" class="demo-form">
                    @csrf
                    <label class="form-field">
                        <span>Full name</span>
                        <input type="text" name="name" required placeholder="Your full name"/>
                    </label>
                    <label class="form-field">
                        <span>Organisation / company (optional)</span>
                        <input type="text" name="organisation" placeholder="Company or business name"/>
                    </label>
                    <label class="form-field">
                        <span>Email address</span>
                        <input type="email" name="email" required placeholder="name@example.com"/>
                    </label>
                    <label class="form-field">
                        <span>Password</span>
                        <input type="password" name="password" required placeholder="Create a strong password"/>
                        <button type="button" class="toggle-pass" data-password-toggle>Show</button>
                    </label>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">Create client account <span>→</span></button>
                    </div>
                </form>
                <div class="auth-links">
                    <a href="{{ route('pages.show', 'login') }}">← Back to sign in</a>
                    <a href="{{ route('pages.show', 'verify-email') }}">Verify email status</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
