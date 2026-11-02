@extends('layouts.app')

@section('title', 'Notifications | DhakaFin')
@section('meta_description', 'Follow important updates and due dates in one organised feed.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Client Workspace" data-kind="notifications" data-page="notifications"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Client Workspace">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'explore') }}">EXPLORE</a><span>—</span>
                NOTIFICATIONS
            </div>
            <div class="v2-overline"><i></i>ALERTS & UPDATES <span>·</span> DHAKAFIN PLATFORM</div>
            <h1>Important updates,<br/><em>in one feed.</em></h1>
            <p>Stay ahead of NBR filing dates, VAT return deadlines, and advisory milestones.</p>
            <div class="v2-hero-links">
                <a href="{{ route('pages.show', 'tax-deadlines') }}">Open Deadline Tracker <svg aria-hidden="true" class="ico" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" viewBox="0 0 24 24" width="16"><path d="M4 12h16m-7-7 7 7-7 7"></path></svg></a>
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
                    <div class="section-kicker">CLIENT WORKSPACE / NOTIFICATIONS</div>
                    <h2>Compliance &amp; advisory feed</h2>
                    <p>Real-time alerts from DhakaFin Advisory and the Tax Deadline Tracker.</p>
                </div>
            </div>
            <div>
                @foreach ($cms['notifications'] ?? [] as $note)
                    <div class="notification-item">
                        <div class="notification-dot"></div>
                        <div>
                            <strong>{{ $note['title'] }}</strong>
                            <p>{{ $note['body'] }}</p>
                        </div>
                        <span>{{ $note['tag'] }}</span>
                    </div>
                @endforeach
                @foreach (array_slice($cms['deadlines'] ?? [], 0, 3) as $dl)
                    <div class="notification-item">
                        <div class="notification-dot"></div>
                        <div>
                            <strong>Upcoming Deadline: {{ $dl['title'] }} ({{ $dl['due_date'] }})</strong>
                            <p>{{ $dl['notes'] }}</p>
                        </div>
                        <span>{{ strtoupper($dl['category']) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
