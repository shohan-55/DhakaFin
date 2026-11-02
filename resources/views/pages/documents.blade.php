@extends('layouts.app')

@section('title', 'Document vault | DhakaFin')
@section('meta_description', 'An organised place for financial and tax records.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Client Workspace" data-kind="documents" data-page="documents"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Client Workspace">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>
                DOCUMENT VAULT
            </div>
            <div class="v2-overline"><i></i>RECORDS & ARCHIVE <span>·</span> DHAKAFIN PLATFORM</div>
            <h1>Your documents,<br/><em>safely organised.</em></h1>
            <p>Upload and manage financial statements, TIN/BIN certificates, challans, and tax records.</p>
            <div class="v2-hero-links">
                <a href="{{ route('pages.show', 'client-dashboard') }}">Back to Overview <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
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
                    <div class="section-kicker">CLIENT WORKSPACE / DOCUMENT VAULT</div>
                    <h2>Document vault</h2>
                    <p>Upload and organise your tax certificates, bank statements, and VAT records.</p>
                </div>
            </div>

            <div class="dash-surface">
                <h3>Add new document to vault</h3>
                <form method="POST" action="{{ route('portal.documents.add') }}" enctype="multipart/form-data" class="demo-form">
                    @csrf
                    <div class="form-grid">
                        <label class="form-field">
                            <span>Document title</span>
                            <input type="text" name="title" required placeholder="e.g., Bank Statement Nov 2026.pdf"/>
                        </label>
                        <label class="form-field">
                            <span>Category</span>
                            <select name="category">
                                <option>Tax &amp; Corporate</option>
                                <option>VAT Compliance</option>
                                <option>Accounting &amp; IFRS</option>
                                <option>Audit Evidence</option>
                                <option>Payroll &amp; TDS</option>
                            </select>
                        </label>
                        <label class="form-field">
                            <span>Attach file (optional)</span>
                            <input type="file" name="file"/>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">Save to Document Vault <span>→</span></button>
                    </div>
                </form>
            </div>

            <div class="portal-actions">
                <input type="search" data-filter-input="vault-table" placeholder="Filter documents..."/>
            </div>
            <div class="data-table-wrap" id="vault-table">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Document Name</th>
                            <th>Category</th>
                            <th>Size</th>
                            <th>Date Added</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cms['documents'] ?? [] as $doc)
                            <tr>
                                <td><b>{{ $doc['title'] }}</b></td>
                                <td>{{ $doc['category'] }}</td>
                                <td>{{ $doc['size'] }}</td>
                                <td>{{ $doc['uploaded_at'] }}</td>
                                <td><span class="table-status">{{ $doc['status'] }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('portal.documents.delete') }}" style="display:inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $doc['id'] }}"/>
                                        <button type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
