@extends('layouts.app')

@section('title', 'Library analytics | DhakaFin')
@section('meta_description', 'Engagement trends and publishing activity reports for DhakaFin.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Admin Studio" data-kind="analytics" data-page="admin-library-analytics"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Admin Studio">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'admin-dashboard') }}">ADMIN STUDIO</a><span>—</span>
                LIBRARY ANALYTICS
            </div>
            <div class="v2-overline"><i></i>100% NO-CODE WEBSITE CONTROL <span>·</span> ADMIN STUDIO</div>
            <h1>Engagement trends &amp;<br/><em>publishing metrics.</em></h1>
            <p>Monitor content coverage across the Income Tax Act 2023, VAT & SD Act 2012, Digital Calculators, and Insights.</p>
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
            <div class="portal-head">
                <div>
                    <div class="section-kicker">ADMIN STUDIO / LIBRARY ANALYTICS</div>
                    <h2>Research &amp; tool engagement</h2>
                    <p>Live inventory of published legal chapters, circulars, articles, and calculator configurations.</p>
                </div>
            </div>
            <div class="metric-row">
                <div>
                    <span>STATUTORY PROVISIONS</span>
                    <strong>{{ count($cms['library'] ?? []) }} Active</strong>
                    <small>Indexed for AI Tax Law Search</small>
                </div>
                <div>
                    <span>EDITORIAL ARTICLES</span>
                    <strong>{{ count($cms['articles'] ?? []) }} Published</strong>
                    <small>Live on DhakaFin Insights</small>
                </div>
                <div>
                    <span>DIGITAL CALCULATORS</span>
                    <strong>10 Tools</strong>
                    <small>100% live calculation engine</small>
                </div>
            </div>
            <div class="dashboard-columns">
                <div class="dash-surface">
                    <h3>Search &amp; Calculator Usage Distribution</h3>
                    <p>Relative activity across Personal Tax, Corporate Tax, VAT/Mushak, TDS/VDS, and Law Reader.</p>
                    <div class="sample-bars tall">
                        <i style="height:82%"></i>
                        <i style="height:68%"></i>
                        <i style="height:94%"></i>
                        <i style="height:76%"></i>
                        <i style="height:88%"></i>
                        <i style="height:71%"></i>
                    </div>
                </div>
                <div class="dash-surface">
                    <h3>Coverage Summary</h3>
                    <ul class="task-list">
                        <li><span>Income Tax Act Chapters</span><small>{{ collect($cms['library'] ?? [])->where('type', 'Act Chapter')->count() }} chapters</small></li>
                        <li><span>Circulars &amp; SROs</span><small>{{ collect($cms['library'] ?? [])->where('type', 'Circular / SRO')->count() }} notices</small></li>
                        <li><span>Active Deadlines</span><small>{{ count($cms['deadlines'] ?? []) }} milestones</small></li>
                        <li><span>Team Specialists</span><small>{{ count($cms['team'] ?? []) }} profiles</small></li>
                        <li><span>Mushak 6.3 Invoices</span><small>{{ count($cms['invoices'] ?? []) }} records</small></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
