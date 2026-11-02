@extends('layouts.app')

@section('title', 'Deadline manager | DhakaFin')
@section('meta_description', 'Maintain tax deadlines and compliance notices across DhakaFin.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Admin Studio" data-kind="admin_table" data-page="admin-deadlines"@endsection

@section('content')
@php
    $editId = isset($request) ? (int) $request->query('edit', 0) : (int) request('edit', 0);
    $editingDl = collect($cms['deadlines'] ?? [])->firstWhere('id', $editId);
@endphp
<section class="v2-hero v2-hero-compact" data-area="Admin Studio">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'admin-dashboard') }}">ADMIN STUDIO</a><span>—</span>
                DEADLINE MANAGER
            </div>
            <div class="v2-overline"><i></i>100% NO-CODE WEBSITE CONTROL <span>·</span> ADMIN STUDIO</div>
            <h1>Compliance dates &amp;<br/><em>filing milestones.</em></h1>
            <p>Add, update, or remove statutory filing deadlines displayed on the Tax Deadline Tracker and Client Workspace.</p>
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
                    <div class="section-kicker">ADMIN STUDIO / DEADLINE MANAGER</div>
                    <h2>Tax &amp; VAT compliance deadlines</h2>
                    <p>Manage statutory return filing dates for Personal Tax, Corporate Tax, VAT (Mushak 9.1), and Withholding Tax.</p>
                </div>
                <a class="primary-btn" href="{{ route('pages.show', 'tax-deadlines') }}">View Deadline Tracker ↗</a>
            </div>

            <div class="dash-surface">
                <h3>{{ $editingDl ? 'Edit Deadline #' . $editingDl['id'] : 'Add Compliance Deadline' }}</h3>
                <form method="POST" action="{{ route('admin.deadlines.save') }}" class="demo-form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $editingDl['id'] ?? 0 }}"/>
                    <div class="form-grid">
                        <label class="form-field">
                            <span>Milestone Title</span>
                            <input type="text" name="title" value="{{ $editingDl['title'] ?? '' }}" required placeholder="e.g., Monthly VAT Return (Mushak 9.1)"/>
                        </label>
                        <label class="form-field">
                            <span>Obligation Category</span>
                            <select name="category">
                                @foreach (['Personal tax', 'Company tax', 'VAT return', 'Withholding'] as $cat)
                                    <option value="{{ $cat }}" {{ ($editingDl['category'] ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="form-field">
                            <span>Assessment / Financial Year</span>
                            <input type="text" name="year" value="{{ $editingDl['year'] ?? '2026–2027' }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Due Date</span>
                            <input type="date" name="due_date" value="{{ $editingDl['due_date'] ?? date('Y-m-d') }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Compliance Notes</span>
                            <input type="text" name="notes" value="{{ $editingDl['notes'] ?? '' }}" placeholder="Statutory rule or filing guidance..."/>
                        </label>
                        <label class="form-field">
                            <span>Status</span>
                            <select name="status">
                                <option value="Upcoming" {{ ($editingDl['status'] ?? '') === 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="Active" {{ ($editingDl['status'] ?? '') === 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Scheduled" {{ ($editingDl['status'] ?? '') === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                            </select>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">{{ $editingDl ? 'Update Deadline' : 'Save Deadline' }} <span>→</span></button>
                    </div>
                </form>
            </div>

            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Milestone</th>
                            <th>Category</th>
                            <th>Year</th>
                            <th>Due Date</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cms['deadlines'] ?? [] as $dl)
                            <tr>
                                <td><b>{{ $dl['title'] }}</b></td>
                                <td>{{ $dl['category'] }}</td>
                                <td>{{ $dl['year'] }}</td>
                                <td><b>{{ $dl['due_date'] }}</b></td>
                                <td>{{ $dl['notes'] }}</td>
                                <td><span class="table-status">{{ $dl['status'] }}</span></td>
                                <td>
                                    <a href="{{ route('pages.show', 'admin-deadlines') }}?edit={{ $dl['id'] }}" style="color:#07805b;font-weight:800;margin-right:10px">Edit</a>
                                    <form method="POST" action="{{ route('admin.deadlines.delete') }}" style="display:inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $dl['id'] }}"/>
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
