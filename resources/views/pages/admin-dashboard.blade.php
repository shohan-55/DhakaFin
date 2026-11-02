@extends('layouts.app')

@section('title', 'Admin overview | DhakaFin')
@section('meta_description', 'A modern operations overview for DhakaFin administrators.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Admin Studio" data-kind="admin_dashboard" data-page="admin-dashboard"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Admin Studio">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'admin-dashboard') }}">ADMIN STUDIO</a><span>—</span>
                ADMIN OVERVIEW
            </div>
            <div class="v2-overline"><i></i>100% NO-CODE WEBSITE CONTROL <span>·</span> ADMIN STUDIO</div>
            <h1>Complete control,<br/><em>without coding.</em></h1>
            <p>Manage homepage content, all 72 pages, blog articles, tax rates, deadlines, legal library, team profiles, and enquiries from one studio.</p>
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
                    <div class="section-kicker">ADMIN STUDIO / OPERATIONS OVERVIEW</div>
                    <h2>DhakaFin No-Code Control Center</h2>
                    <p>Every module below is connected live to your public website, calculators, and legal research tools.</p>
                </div>
                <a class="primary-btn" href="{{ route('home') }}?edit=1">✦ Edit Any Page Visually ↗</a>
            </div>
            <div class="metric-row">
                <div>
                    <span>PUBLIC &amp; PORTAL PAGES</span>
                    <strong>72 Pages</strong>
                    <small>100% active &amp; editable via CMS</small>
                </div>
                <div>
                    <span>INBOX ENQUIRIES</span>
                    <strong>{{ count($cms['enquiries'] ?? []) }} Messages</strong>
                    <small>{{ collect($cms['enquiries'] ?? [])->where('status', 'Unread')->count() }} unread enquiries</small>
                </div>
                <div>
                    <span>PUBLISHED RESOURCES</span>
                    <strong>{{ count($cms['articles'] ?? []) + count($cms['library'] ?? []) }} Items</strong>
                    <small>{{ count($cms['articles'] ?? []) }} articles · {{ count($cms['library'] ?? []) }} law provisions</small>
                </div>
            </div>
            <div class="dashboard-columns">
                <div>
                    <div class="dash-surface">
                        <h3>Recent Visitor Enquiries</h3>
                        <p>Submitted via Contact, Consultation, and Homepage forms.</p>
                        <ul class="task-list">
                            @foreach (array_slice($cms['enquiries'] ?? [], 0, 5) as $enq)
                                <li>
                                    <span><b>{{ $enq['name'] }}</b> ({{ $enq['service'] ?: $enq['type'] }}) — {{ \Illuminate\Support\Str::limit($enq['message'], 55) }}</span>
                                    <small>{{ $enq['status'] }}</small>
                                </li>
                            @endforeach
                        </ul>
                        <div class="list-links">
                            <a href="{{ route('pages.show', 'admin-messages') }}">Open full Messages Inbox →</a>
                        </div>
                    </div>
                    <div class="dash-surface">
                        <h3>Live Website Modules</h3>
                        <p>Activity distribution across Advisory, Calculators, Tax Research, and Workspace.</p>
                        <div class="sample-bars">
                            <i style="height:75%"></i>
                            <i style="height:88%"></i>
                            <i style="height:64%"></i>
                            <i style="height:95%"></i>
                            <i style="height:82%"></i>
                            <i style="height:90%"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="dash-surface">
                        <h3>No-Code Studio Managers</h3>
                        <p>Select any section to edit website content immediately:</p>
                        <div class="list-links">
                            <a href="{{ route('pages.show', 'admin-homepage') }}">Homepage &amp; All-Pages Editor ↗</a>
                            <a href="{{ route('pages.show', 'admin-blog') }}">Blog &amp; Insights Manager ({{ count($cms['articles'] ?? []) }}) ↗</a>
                            <a href="{{ route('pages.show', 'admin-tax-rates') }}">Calculator Tax &amp; VAT Rates ↗</a>
                            <a href="{{ route('pages.show', 'admin-deadlines') }}">Tax Deadlines Manager ({{ count($cms['deadlines'] ?? []) }}) ↗</a>
                            <a href="{{ route('pages.show', 'admin-library') }}">Income Tax Act &amp; Circulars ({{ count($cms['library'] ?? []) }}) ↗</a>
                            <a href="{{ route('pages.show', 'admin-portfolio') }}">Team &amp; Portfolio Manager ({{ count($cms['team'] ?? []) }}) ↗</a>
                            <a href="{{ route('pages.show', 'admin-footer') }}">Footer &amp; Brand Manager ↗</a>
                            <a href="{{ route('pages.show', 'admin-settings') }}">Global Site Settings ↗</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
