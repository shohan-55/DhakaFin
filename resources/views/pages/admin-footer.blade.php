@extends('layouts.app')

@section('title', 'Footer manager | DhakaFin')
@section('meta_description', 'Manage footer copy, copyright notice, and support messaging across DhakaFin.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Admin Studio" data-kind="admin_form" data-page="admin-footer"@endsection

@section('content')
@php
    $footer = $cms['footer'] ?? [];
@endphp
<section class="v2-hero v2-hero-compact" data-area="Admin Studio">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'admin-dashboard') }}">ADMIN STUDIO</a><span>—</span>
                FOOTER MANAGER
            </div>
            <div class="v2-overline"><i></i>100% NO-CODE WEBSITE CONTROL <span>·</span> ADMIN STUDIO</div>
            <h1>Footer branding &amp;<br/><em>legal copy.</em></h1>
            <p>Update the footer description, support note, and copyright line displayed across every page of the website.</p>
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
                    <div class="section-kicker">ADMIN STUDIO / FOOTER MANAGER</div>
                    <h2>Global footer configuration</h2>
                    <p>Changes saved here apply immediately to the footer across all pages.</p>
                </div>
            </div>
            <div class="dash-surface">
                <form method="POST" action="{{ route('admin.footer.save') }}" class="panel-form demo-form">
                    @csrf
                    <div class="form-grid">
                        <label class="form-field">
                            <span>Inner Footer Description</span>
                            <input type="text" name="description" value="{{ $footer['description'] ?? '' }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Homepage Footer Description</span>
                            <input type="text" name="home_description" value="{{ $footer['home_description'] ?? '' }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Copyright Text</span>
                            <input type="text" name="copyright" value="{{ $footer['copyright'] ?? '© 2026 DhakaFin · All rights reserved' }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Support Note</span>
                            <input type="text" name="support_note" value="{{ $footer['support_note'] ?? 'For tax assistance and professional advice, contact our team.' }}" required/>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">Save Footer Settings <span>→</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
