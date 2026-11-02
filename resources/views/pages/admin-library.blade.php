@extends('layouts.app')

@section('title', 'Legal library manager | DhakaFin')
@section('meta_description', 'Organise books, chapters, sections, and circulars for the DhakaFin tax research library.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Admin Studio" data-kind="admin_table" data-page="admin-library"@endsection

@section('content')
@php
    $editId = isset($request) ? (int) $request->query('edit', 0) : (int) request('edit', 0);
    $editingLib = collect($cms['library'] ?? [])->firstWhere('id', $editId);
@endphp
<section class="v2-hero v2-hero-compact" data-area="Admin Studio">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'admin-dashboard') }}">ADMIN STUDIO</a><span>—</span>
                LEGAL LIBRARY MANAGER
            </div>
            <div class="v2-overline"><i></i>100% NO-CODE WEBSITE CONTROL <span>·</span> ADMIN STUDIO</div>
            <h1>Tax law, chapters<br/><em>&amp; SRO library.</em></h1>
            <p>Manage chapters of the Income Tax Act 2023, VAT & SD Act 2012, and NBR Circulars powering the Law Reader and AI Tax Search.</p>
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
                    <div class="section-kicker">ADMIN STUDIO / LEGAL LIBRARY MANAGER</div>
                    <h2>Statutes, chapters &amp; circulars</h2>
                    <p>Items managed here dynamically populate the Income Tax Act Reader, Tax Library, Circulars, and AI Law Search.</p>
                </div>
                <a class="primary-btn" href="{{ route('pages.show', 'tax-act') }}">Open Law Reader ↗</a>
            </div>

            <div class="dash-surface">
                <h3>{{ $editingLib ? 'Edit Provision: ' . $editingLib['code'] . ' — ' . $editingLib['title'] : 'Add New Chapter / Circular / SRO' }}</h3>
                <form method="POST" action="{{ route('admin.library.save') }}" class="demo-form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $editingLib['id'] ?? 0 }}"/>
                    <div class="form-grid">
                        <label class="form-field">
                            <span>Code (e.g., CH 07 or SRO 02)</span>
                            <input type="text" name="code" value="{{ $editingLib['code'] ?? '' }}" required placeholder="CH 07"/>
                        </label>
                        <label class="form-field">
                            <span>Resource Type</span>
                            <select name="type">
                                <option value="Act Chapter" {{ ($editingLib['type'] ?? '') === 'Act Chapter' ? 'selected' : '' }}>Act Chapter (Income Tax Act Reader)</option>
                                <option value="Circular / SRO" {{ ($editingLib['type'] ?? '') === 'Circular / SRO' ? 'selected' : '' }}>Circular / SRO (Circulars &amp; Library)</option>
                            </select>
                        </label>
                        <label class="form-field">
                            <span>Title</span>
                            <input type="text" name="title" value="{{ $editingLib['title'] ?? '' }}" required placeholder="e.g., International Tax & Transfer Pricing"/>
                        </label>
                        <label class="form-field">
                            <span>Book / Statute Header</span>
                            <input type="text" name="book" value="{{ $editingLib['book'] ?? 'BOOK 01 / INCOME TAX ACT 2023' }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Lead Summary</span>
                            <textarea name="lead" rows="2" required placeholder="Summary of the chapter or circular...">{{ $editingLib['lead'] ?? '' }}</textarea>
                        </label>
                        <label class="form-field">
                            <span>Detailed Overview</span>
                            <textarea name="overview" rows="3" required placeholder="Detailed explanation displayed in the reader and AI search...">{{ $editingLib['overview'] ?? '' }}</textarea>
                        </label>
                        <label class="form-field">
                            <span>Statutory Quote / Highlighted Provision</span>
                            <textarea name="quote" rows="2" required placeholder="Key statutory quote highlighted in the reader...">{{ $editingLib['quote'] ?? '' }}</textarea>
                        </label>
                        <label class="form-field">
                            <span>Navigation / Practitioner Note</span>
                            <input type="text" name="navigation_note" value="{{ $editingLib['navigation_note'] ?? 'Cross-reference with official NBR notifications.' }}"/>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">{{ $editingLib ? 'Update Provision' : 'Add to Legal Library' }} <span>→</span></button>
                    </div>
                </form>
            </div>

            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Statute / Book</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cms['library'] ?? [] as $lib)
                            <tr>
                                <td><b>{{ $lib['code'] }}</b></td>
                                <td>{{ $lib['type'] }}</td>
                                <td><b>{{ $lib['title'] }}</b></td>
                                <td>{{ $lib['book'] }}</td>
                                <td><span class="table-status">{{ $lib['status'] ?? 'Published' }}</span></td>
                                <td>
                                    <a href="{{ route('pages.show', 'admin-library') }}?edit={{ $lib['id'] }}" style="color:#07805b;font-weight:800;margin-right:10px">Edit</a>
                                    <a href="{{ route('pages.show', 'tax-act-section') }}?id={{ $lib['id'] }}" style="color:#07805b;font-weight:800;margin-right:10px">Read ↗</a>
                                    <form method="POST" action="{{ route('admin.library.delete') }}" style="display:inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $lib['id'] }}"/>
                                        <button type="submit" style="color:#b91c1c">Delete</button>
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
