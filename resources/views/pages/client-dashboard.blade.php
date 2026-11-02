@extends('layouts.app')

@section('title', 'Client overview | DhakaFin')
@section('meta_description', 'A clear home for personal and business financial activity.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Client Workspace" data-kind="client_dashboard" data-page="client-dashboard"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Client Workspace">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>
                CLIENT OVERVIEW
            </div>
            <div class="v2-overline"><i></i>CLIENT WORKSPACE <span>·</span> DHAKAFIN PLATFORM</div>
            <h1>A clear home for<br/><em>financial activity.</em></h1>
            <p>Track your documents, Mushak 6.3 invoices, tax submissions, and compliance deadlines in one place.</p>
            <div class="v2-hero-links">
                <a href="{{ route('pages.show', 'mushak-6-3') }}">Create Mushak 6.3 <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
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
                    <div class="section-kicker">CLIENT WORKSPACE / OVERVIEW</div>
                    <h2>Welcome, {{ $cms['admin_credentials']['client_name'] ?? 'DhakaFin Client' }}</h2>
                    <p>Summary of your documents, Mushak 6.3 VAT invoices, tax submissions, and upcoming Bangladesh compliance deadlines.</p>
                </div>
                <a class="primary-btn" href="{{ route('pages.show', 'mushak-6-3') }}">New Mushak 6.3 Invoice ↗</a>
            </div>
            <div class="metric-row">
                <div>
                    <span>DOCUMENT VAULT</span>
                    <strong>{{ count($cms['documents'] ?? []) }} Files</strong>
                    <small>Verified financial &amp; tax records</small>
                </div>
                <div>
                    <span>MUSHAK 6.3 INVOICES</span>
                    <strong>{{ count($cms['invoices'] ?? []) }} Issued</strong>
                    <small>Connected with VAT Creator</small>
                </div>
                <div>
                    <span>TAX SUBMISSIONS</span>
                    <strong>{{ count($cms['submissions'] ?? []) }} Active</strong>
                    <small>AY 2026–2027 compliance on track</small>
                </div>
            </div>
            <div class="dashboard-columns">
                <div>
                    <div class="dash-surface">
                        <h3>Recent Mushak 6.3 Invoices</h3>
                        <p>Invoices generated from the Mushak 6.3 tool appear here automatically.</p>
                        <ul class="task-list">
                            @foreach (array_slice($cms['invoices'] ?? [], 0, 4) as $inv)
                                <li>
                                    <span><b>{{ $inv['invoice_no'] }}</b> — {{ $inv['buyer'] }} ({{ $inv['item'] }})</span>
                                    <small>৳ {{ number_format((float) $inv['total']) }}</small>
                                </li>
                            @endforeach
                        </ul>
                        <div class="list-links">
                            <a href="{{ route('pages.show', 'mushak-list') }}">View all Mushak 6.3 invoices →</a>
                        </div>
                    </div>
                    <div class="dash-surface">
                        <h3>Upcoming Compliance Deadlines</h3>
                        <p>Managed live from the DhakaFin Deadline Tracker.</p>
                        <ul class="task-list">
                            @foreach (array_slice($cms['deadlines'] ?? [], 0, 4) as $dl)
                                <li>
                                    <span><b>{{ $dl['title'] }}</b> ({{ $dl['category'] }})</span>
                                    <small>Due: {{ $dl['due_date'] }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div>
                    <div class="dash-surface">
                        <h3>Quick Digital Tools</h3>
                        <p>Launch any calculator or compliance workflow.</p>
                        <div class="list-links">
                            <a href="{{ route('pages.show', 'personal-tax') }}">Personal Income Tax Calculator ↗</a>
                            <a href="{{ route('pages.show', 'corporate-tax') }}">Corporate Tax Calculator ↗</a>
                            <a href="{{ route('pages.show', 'tds-calculator') }}">TDS Withholding Calculator ↗</a>
                            <a href="{{ route('pages.show', 'vds-calculator') }}">VDS Withholding Calculator ↗</a>
                            <a href="{{ route('pages.show', 'vat-calculator') }}">VAT Calculator ↗</a>
                            <a href="{{ route('pages.show', 'vat-return') }}">Mushak 9.1 VAT Return ↗</a>
                            <a href="{{ route('pages.show', 'documents') }}">Upload to Document Vault ↗</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
