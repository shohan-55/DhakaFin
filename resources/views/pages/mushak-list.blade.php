@extends('layouts.app')

@section('title', 'Mushak invoices | DhakaFin')
@section('meta_description', 'Browse drafts, submitted invoices, and archived Mushak 6.3 records.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Client Workspace" data-kind="table" data-page="mushak-list"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Client Workspace">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>
                MUSHAK INVOICES
            </div>
            <div class="v2-overline"><i></i>VAT RECORDS <span>·</span> DHAKAFIN PLATFORM</div>
            <h1>Mushak 6.3<br/><em>invoice register.</em></h1>
            <p>All VAT invoices generated via the Mushak 6.3 Creator are listed here with printable views.</p>
            <div class="v2-hero-links">
                <a href="{{ route('pages.show', 'mushak-6-3') }}">Create New Mushak 6.3 <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
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
                    <div class="section-kicker">CLIENT WORKSPACE / MUSHAK 6.3 REGISTER</div>
                    <h2>Mushak invoices</h2>
                    <p>Click any invoice to open its printable Mushak 6.3 tax challan view.</p>
                </div>
                <a class="primary-btn" href="{{ route('pages.show', 'mushak-6-3') }}">Create Mushak 6.3 ↗</a>
            </div>
            <div class="portal-actions">
                <input type="search" data-filter-input="mushak-table" placeholder="Search by invoice no, buyer or item..."/>
            </div>
            <div class="data-table-wrap" id="mushak-table">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Challan No.</th>
                            <th>Issue Date</th>
                            <th>Recipient / Buyer</th>
                            <th>Description of Supply</th>
                            <th>VAT (15%)</th>
                            <th>Total Value</th>
                            <th>Status</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cms['invoices'] ?? [] as $inv)
                            <tr>
                                <td><b>{{ $inv['invoice_no'] }}</b></td>
                                <td>{{ $inv['date'] }}</td>
                                <td>{{ $inv['buyer'] }}</td>
                                <td>{{ $inv['item'] }}</td>
                                <td>৳ {{ number_format((float) $inv['vat_amount'], 2) }}</td>
                                <td><b>৳ {{ number_format((float) $inv['total'], 2) }}</b></td>
                                <td><span class="table-status">{{ $inv['status'] }}</span></td>
                                <td><a href="{{ route('pages.show', 'mushak-detail') }}?id={{ $inv['id'] }}" style="color:#07805b;font-weight:800">View / Print ↗</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
