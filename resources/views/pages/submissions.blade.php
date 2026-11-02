@extends('layouts.app')

@section('title', 'Tax submissions | DhakaFin')
@section('meta_description', 'Track planned and completed tax-related workstreams.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Client Workspace" data-kind="table" data-page="submissions"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Client Workspace">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>
                TAX SUBMISSIONS
            </div>
            <div class="v2-overline"><i></i>COMPLIANCE TRACKING <span>·</span> DHAKAFIN PLATFORM</div>
            <h1>Track every<br/><em>tax submission.</em></h1>
            <p>Log and monitor income tax returns, monthly VAT returns, and withholding tax statements.</p>
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
                    <div class="section-kicker">CLIENT WORKSPACE / SUBMISSIONS</div>
                    <h2>Tax &amp; VAT submissions</h2>
                    <p>Record and track return filings across Income Tax, VAT (Mushak 9.1), and Withholding Tax.</p>
                </div>
            </div>

            <div class="dash-surface">
                <h3>Log a new return submission</h3>
                <form method="POST" action="{{ route('portal.submissions.add') }}" class="demo-form">
                    @csrf
                    <div class="form-grid">
                        <label class="form-field">
                            <span>Submission title</span>
                            <input type="text" name="title" required placeholder="e.g., Monthly VAT Return — November 2026"/>
                        </label>
                        <label class="form-field">
                            <span>Return type</span>
                            <select name="type">
                                <option>VAT Return</option>
                                <option>Income Tax</option>
                                <option>TDS Return</option>
                                <option>Corporate Tax</option>
                            </select>
                        </label>
                        <label class="form-field">
                            <span>Tax period / year</span>
                            <input type="text" name="period" value="2026–2027" required/>
                        </label>
                        <label class="form-field">
                            <span>Challan / Tax amount (৳)</span>
                            <input type="number" name="amount" min="0" step="any" value="45000" required/>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">Add Submission Record <span>→</span></button>
                    </div>
                </form>
            </div>

            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Submission Title</th>
                            <th>Type</th>
                            <th>Period</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cms['submissions'] ?? [] as $sub)
                            <tr>
                                <td><b>{{ $sub['reference'] }}</b></td>
                                <td>{{ $sub['title'] }}</td>
                                <td>{{ $sub['type'] }}</td>
                                <td>{{ $sub['period'] }}</td>
                                <td><b>{{ $sub['amount'] }}</b></td>
                                <td><span class="table-status">{{ $sub['status'] }}</span></td>
                                <td>{{ $sub['updated_at'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
