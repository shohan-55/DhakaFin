@extends('layouts.app')

@section('title', 'Messages | DhakaFin')
@section('meta_description', 'A unified inbox for service enquiries and consultation requests.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Admin Studio" data-kind="admin_table" data-page="admin-messages"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Admin Studio">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'admin-dashboard') }}">ADMIN STUDIO</a><span>—</span>
                MESSAGES INBOX
            </div>
            <div class="v2-overline"><i></i>100% NO-CODE WEBSITE CONTROL <span>·</span> ADMIN STUDIO</div>
            <h1>Client enquiries &amp;<br/><em>consultation requests.</em></h1>
            <p>Every form submitted on the Contact page, Consultation page, or Homepage dialog is logged here automatically.</p>
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
                    <div class="section-kicker">ADMIN STUDIO / UNIFIED INBOX</div>
                    <h2>Website messages &amp; consultation requests</h2>
                    <p>Review enquiries, update their status, or reply directly via email.</p>
                </div>
                <a class="primary-btn" href="{{ route('pages.show', 'contact') }}">View Contact Form ↗</a>
            </div>
            <div class="portal-actions">
                <input type="search" data-filter-input="inbox-table" placeholder="Search messages by name, email or service..."/>
            </div>
            <div class="data-table-wrap" id="inbox-table">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sender</th>
                            <th>Service / Type</th>
                            <th>Message</th>
                            <th>Received</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cms['enquiries'] ?? [] as $enq)
                            <tr>
                                <td><b>#{{ $enq['id'] }}</b></td>
                                <td>
                                    <b>{{ $enq['name'] }}</b><br/>
                                    <small>{{ $enq['email'] }} {{ !empty($enq['organisation']) ? '· ' . $enq['organisation'] : '' }}</small>
                                </td>
                                <td>
                                    {{ $enq['service'] ?: ucfirst($enq['type']) }}
                                    @if (!empty($enq['preferred_date']))
                                        <br/><small>Pref: {{ $enq['preferred_date'] }}</small>
                                    @endif
                                </td>
                                <td>{{ $enq['message'] }}</td>
                                <td>{{ $enq['created_at'] }}</td>
                                <td><span class="table-status">{{ $enq['status'] ?? 'Unread' }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.messages.status') }}" style="display:inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $enq['id'] }}"/>
                                        <input type="hidden" name="status" value="{{ ($enq['status'] ?? 'Unread') === 'Unread' ? 'Read' : 'Replied' }}"/>
                                        <button type="submit">Mark {{ ($enq['status'] ?? 'Unread') === 'Unread' ? 'Read' : 'Replied' }}</button>
                                    </form>
                                    <a href="mailto:{{ $enq['email'] }}?subject=Re: DhakaFin Enquiry" style="color:#07805b;font-weight:800;margin:0 8px">Reply ↗</a>
                                    <form method="POST" action="{{ route('admin.messages.delete') }}" style="display:inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $enq['id'] }}"/>
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
