@extends('layouts.app')

@section('title', 'Reports & analytics | DhakaFin')
@section('meta_description', 'Explore finance reports, charts, and structured performance snapshots.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Client Workspace" data-kind="analytics" data-page="reports"@endsection

@section('content')
@php
    $totalInvoiced = collect($cms['invoices'] ?? [])->sum('total');
    $totalVat = collect($cms['invoices'] ?? [])->sum('vat_amount');
@endphp
<section class="v2-hero v2-hero-compact" data-area="Client Workspace">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>
                REPORTS & ANALYTICS
            </div>
            <div class="v2-overline"><i></i>FINANCIAL INTELLIGENCE <span>·</span> DHAKAFIN PLATFORM</div>
            <h1>Performance &amp;<br/><em>compliance reports.</em></h1>
            <p>Review aggregated VAT invoicing, withholding summaries, and compliance readiness metrics.</p>
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
            <div class="portal-head">
                <div>
                    <div class="section-kicker">CLIENT WORKSPACE / ANALYTICS</div>
                    <h2>Reports &amp; financial snapshots</h2>
                    <p>Real-time summary computed from your Mushak invoices, submissions, and statutory rates.</p>
                </div>
                <button type="button" class="primary-btn" onclick="window.print()">Export Summary PDF ↗</button>
            </div>
            <div class="metric-row">
                <div>
                    <span>TOTAL MUSHAK 6.3 VALUE</span>
                    <strong>৳ {{ number_format((float) $totalInvoiced) }}</strong>
                    <small>Across {{ count($cms['invoices'] ?? []) }} invoices</small>
                </div>
                <div>
                    <span>OUTPUT VAT RECORDED</span>
                    <strong>৳ {{ number_format((float) $totalVat) }}</strong>
                    <small>Standard {{ $cms['tax_rates']['vat_standard_rate'] ?? 15 }}% VAT rate</small>
                </div>
                <div>
                    <span>COMPLIANCE READINESS</span>
                    <strong>100%</strong>
                    <small>{{ count($cms['documents'] ?? []) }} documents verified</small>
                </div>
            </div>
            <div class="dashboard-columns">
                <div class="dash-surface">
                    <h3>Monthly Compliance &amp; VAT Activity</h3>
                    <p>Visual distribution across tax periods in AY 2026–2027.</p>
                    <div class="sample-bars tall">
                        <i style="height:45%" title="May"></i>
                        <i style="height:62%" title="Jun"></i>
                        <i style="height:54%" title="Jul"></i>
                        <i style="height:78%" title="Aug"></i>
                        <i style="height:85%" title="Sep"></i>
                        <i style="height:92%" title="Oct"></i>
                        <i style="height:74%" title="Nov"></i>
                    </div>
                </div>
                <div class="dash-surface">
                    <h3>Current Statutory Rates in Effect</h3>
                    <p>Synchronised with Admin Studio Tax Rate Manager.</p>
                    <ul class="task-list">
                        <li><span>Personal Tax Exemption</span><small>৳ {{ number_format((float) ($cms['tax_rates']['personal_general_threshold'] ?? 350000)) }}</small></li>
                        <li><span>Private Company Tax</span><small>{{ $cms['tax_rates']['corporate_private_rate'] ?? 27.5 }}%</small></li>
                        <li><span>Listed Company Tax</span><small>{{ $cms['tax_rates']['corporate_listed_rate'] ?? 20 }}%</small></li>
                        <li><span>Standard VAT Rate</span><small>{{ $cms['tax_rates']['vat_standard_rate'] ?? 15 }}%</small></li>
                        <li><span>TDS Professional Services</span><small>{{ $cms['tax_rates']['tds_professional_rate'] ?? 10 }}%</small></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
