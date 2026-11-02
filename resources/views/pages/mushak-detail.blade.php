@extends('layouts.app')

@section('title', 'Invoice details | DhakaFin')
@section('meta_description', 'View a Mushak 6.3 tax invoice with print-ready composition.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Client Workspace" data-kind="invoice" data-page="mushak-detail"@endsection

@section('content')
@php
    $invoices = $cms['invoices'] ?? [];
    $reqId = isset($request) ? (int) $request->query('id', 0) : (int) request('id', 0);
    $invoice = collect($invoices)->firstWhere('id', $reqId) ?? ($invoices[0] ?? [
        'id' => 1,
        'invoice_no' => 'M63-2026-001',
        'date' => date('Y-m-d'),
        'supplier' => 'DhakaFin Advisory Ltd.',
        'buyer' => 'Horizon Manufacturing Ltd.',
        'item' => 'Financial Reporting & VAT Compliance Services',
        'qty' => 1,
        'price' => 85000,
        'vat_rate' => 15,
        'vat_amount' => 12750,
        'total' => 97750,
        'status' => 'Issued',
    ]);
    $subtotal = (float) $invoice['qty'] * (float) $invoice['price'];
@endphp
<section class="v2-hero v2-hero-compact" data-area="Client Workspace">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>
                INVOICE DETAILS
            </div>
            <div class="v2-overline"><i></i>MUSHAK 6.3 TAX CHALLAN <span>·</span> DHAKAFIN PLATFORM</div>
            <h1>Mushak 6.3<br/><em>invoice detail.</em></h1>
            <p>Print-ready VAT tax challan representation prepared in DhakaFin.</p>
            <div class="v2-hero-links">
                <a href="{{ route('pages.show', 'mushak-list') }}">Back to Invoice List <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
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
                    <div class="section-kicker">FORM MUSHAK 6.3 / TAX INVOICE</div>
                    <h2>Challan {{ $invoice['invoice_no'] }}</h2>
                    <p>Issued under the Value Added Tax and Supplementary Duty Act, 2012.</p>
                </div>
                <button type="button" class="primary-btn" onclick="window.print()">Print Mushak 6.3 ↗</button>
            </div>
            <div class="invoice-paper">
                <div class="invoice-header">
                    <div>
                        <strong>Mushak 6.3 — Tax Invoice</strong>
                        <small>Government of the People's Republic of Bangladesh · National Board of Revenue</small>
                    </div>
                    <span>STATUS: {{ strtoupper($invoice['status'] ?? 'ISSUED') }}</span>
                </div>
                <div class="invoice-cols">
                    <div>
                        <span>SUPPLIER / REGISTERED PERSON</span><br/>
                        <b>{{ $invoice['supplier'] }}</b><br/>
                        Address: {{ $cms['settings']['address'] ?? 'Gulshan-2, Dhaka 1212, Bangladesh' }}<br/>
                        Contact: {{ $cms['settings']['email'] ?? 'hello.sohan@outlook.com' }}
                    </div>
                    <div>
                        <span>RECIPIENT / PURCHASER</span><br/>
                        <b>{{ $invoice['buyer'] }}</b><br/>
                        Invoice / Challan No: <b>{{ $invoice['invoice_no'] }}</b><br/>
                        Issue Date: <b>{{ $invoice['date'] }}</b>
                    </div>
                </div>
                <div class="data-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Description of Taxable Supply</th>
                                <th>Qty</th>
                                <th>Unit Value (৳)</th>
                                <th>Base Value (৳)</th>
                                <th>VAT Rate</th>
                                <th>VAT Amount (৳)</th>
                                <th>Total incl. VAT (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td><b>{{ $invoice['item'] }}</b></td>
                                <td>{{ $invoice['qty'] }}</td>
                                <td>৳ {{ number_format((float) $invoice['price'], 2) }}</td>
                                <td>৳ {{ number_format($subtotal, 2) }}</td>
                                <td>{{ $invoice['vat_rate'] }}%</td>
                                <td>৳ {{ number_format((float) $invoice['vat_amount'], 2) }}</td>
                                <td><b>৳ {{ number_format((float) $invoice['total'], 2) }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="invoice-note">
                    Note: Verify BIN registration, Treasury Challan, and VDS (Mushak 6.6) applicability before filing monthly Mushak 9.1 return.
                </p>
                <div class="form-actions">
                    <a href="{{ route('pages.show', 'mushak-list') }}" class="secondary-light">← All Mushak Invoices</a>
                    <a href="{{ route('pages.show', 'mushak-6-3') }}" class="primary-btn">Create Another Invoice ↗</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
