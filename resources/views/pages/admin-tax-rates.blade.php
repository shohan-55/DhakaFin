@extends('layouts.app')

@section('title', 'Tax rate manager | DhakaFin')
@section('meta_description', 'Manage statutory rates for Personal Tax, Corporate Tax, TDS, VDS, and VAT across all DhakaFin calculators.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Admin Studio" data-kind="admin_table" data-page="admin-tax-rates"@endsection

@section('content')
@php
    $rates = $cms['tax_rates'] ?? [];
@endphp
<section class="v2-hero v2-hero-compact" data-area="Admin Studio">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'admin-dashboard') }}">ADMIN STUDIO</a><span>—</span>
                TAX RATE MANAGER
            </div>
            <div class="v2-overline"><i></i>100% NO-CODE WEBSITE CONTROL <span>·</span> ADMIN STUDIO</div>
            <h1>Statutory tax, VAT,<br/><em>TDS &amp; VDS rates.</em></h1>
            <p>Update the rates and exemption thresholds below to dynamically adjust all 10 DhakaFin calculators and the Tax Rate Reference.</p>
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
                    <div class="section-kicker">ADMIN STUDIO / TAX RATE MANAGER</div>
                    <h2>Calculator &amp; reference rate configuration</h2>
                    <p>Any change saved here immediately updates the Personal Tax, Corporate Tax, TDS, VDS, VAT, and Mushak 6.3 calculators.</p>
                </div>
                <a class="primary-btn" href="{{ route('pages.show', 'tax-rates') }}">View Tax Rate Reference ↗</a>
            </div>

            <div class="dash-surface">
                <h3>Statutory Thresholds &amp; Rates (AY 2026–2027)</h3>
                <form method="POST" action="{{ route('admin.tax-rates.save') }}" class="demo-form">
                    @csrf
                    <div class="form-grid">
                        <label class="form-field">
                            <span>Personal Tax-Free Threshold — General (৳)</span>
                            <input type="number" step="any" name="personal_general_threshold" value="{{ $rates['personal_general_threshold'] ?? 350000 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Personal Tax-Free Threshold — Female / Senior (৳)</span>
                            <input type="number" step="any" name="personal_female_senior_threshold" value="{{ $rates['personal_female_senior_threshold'] ?? 400000 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Personal Tax-Free Threshold — Other / Special (৳)</span>
                            <input type="number" step="any" name="personal_other_threshold" value="{{ $rates['personal_other_threshold'] ?? 475000 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Eligible Investment Tax Rebate Rate (%)</span>
                            <input type="number" step="any" name="personal_rebate_rate" value="{{ $rates['personal_rebate_rate'] ?? 15 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Corporate Tax — Private Company (%)</span>
                            <input type="number" step="any" name="corporate_private_rate" value="{{ $rates['corporate_private_rate'] ?? 27.5 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Corporate Tax — Listed Company (%)</span>
                            <input type="number" step="any" name="corporate_listed_rate" value="{{ $rates['corporate_listed_rate'] ?? 20.0 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Corporate Tax — Other Entity (%)</span>
                            <input type="number" step="any" name="corporate_other_rate" value="{{ $rates['corporate_other_rate'] ?? 30.0 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Standard VAT Rate (Mushak 6.3 / 9.1) (%)</span>
                            <input type="number" step="any" name="vat_standard_rate" value="{{ $rates['vat_standard_rate'] ?? 15.0 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Reduced / Truncated VAT Rate (%)</span>
                            <input type="number" step="any" name="vat_reduced_rate" value="{{ $rates['vat_reduced_rate'] ?? 7.5 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>TDS Rate — Professional Services (%)</span>
                            <input type="number" step="any" name="tds_professional_rate" value="{{ $rates['tds_professional_rate'] ?? 10.0 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>TDS Rate — House Property Rent (%)</span>
                            <input type="number" step="any" name="tds_rent_rate" value="{{ $rates['tds_rent_rate'] ?? 5.0 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>TDS Rate — Supplies (%)</span>
                            <input type="number" step="any" name="tds_supplies_rate" value="{{ $rates['tds_supplies_rate'] ?? 5.0 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>TDS Rate — Contracts (%)</span>
                            <input type="number" step="any" name="tds_contract_rate" value="{{ $rates['tds_contract_rate'] ?? 7.0 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>VDS Rate — Professional / Rent (%)</span>
                            <input type="number" step="any" name="vds_professional_rate" value="{{ $rates['vds_professional_rate'] ?? 15.0 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>VDS Rate — Supplies (%)</span>
                            <input type="number" step="any" name="vds_supplies_rate" value="{{ $rates['vds_supplies_rate'] ?? 7.5 }}" required/>
                        </label>
                        <label class="form-field">
                            <span>VDS Rate — Contracts (%)</span>
                            <input type="number" step="any" name="vds_contract_rate" value="{{ $rates['vds_contract_rate'] ?? 10.0 }}" required/>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">Save &amp; Sync All Calculators <span>→</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
