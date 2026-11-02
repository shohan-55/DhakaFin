@extends('layouts.app')

@section('title', 'Site settings | DhakaFin')
@section('meta_description', 'Adjust branding, contact details, SEO metadata, and global DhakaFin settings.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Admin Studio" data-kind="admin_form" data-page="admin-settings"@endsection

@section('content')
@php
    $settings = $cms['settings'] ?? [];
@endphp
<section class="v2-hero v2-hero-compact" data-area="Admin Studio">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'admin-dashboard') }}">ADMIN STUDIO</a><span>—</span>
                SITE SETTINGS
            </div>
            <div class="v2-overline"><i></i>100% NO-CODE WEBSITE CONTROL <span>·</span> ADMIN STUDIO</div>
            <h1>Global identity &amp;<br/><em>contact settings.</em></h1>
            <p>Update the primary contact email, phone number, office address, SEO description, and live editor settings.</p>
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
                    <div class="section-kicker">ADMIN STUDIO / SITE SETTINGS</div>
                    <h2>Brand &amp; contact configuration</h2>
                    <p>Updating the contact email or phone number here updates it across all pages, forms, and JSON-LD schemas.</p>
                </div>
                <a class="primary-btn" href="{{ route('home') }}?edit=1">✦ Launch Live On-Page Editor ↗</a>
            </div>
            <div class="dash-surface">
                <h3>Global Site Settings</h3>
                <form method="POST" action="{{ route('admin.settings.save') }}" class="demo-form">
                    @csrf
                    <div class="form-grid">
                        <label class="form-field">
                            <span>Site Name</span>
                            <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'DhakaFin' }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Brand Tagline</span>
                            <input type="text" name="tagline" value="{{ $settings['tagline'] ?? 'FINANCIAL INTELLIGENCE' }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Primary Advisory Email</span>
                            <input type="email" name="email" value="{{ $settings['email'] ?? 'hello.sohan@outlook.com' }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Primary Phone Number</span>
                            <input type="text" name="phone" value="{{ $settings['phone'] ?? '+8801865555767' }}" required/>
                        </label>
                        <label class="form-field">
                            <span>Office Address</span>
                            <input type="text" name="address" value="{{ $settings['address'] ?? 'Gulshan-2, Dhaka 1212, Bangladesh' }}"/>
                        </label>
                        <label class="form-field">
                            <span>Default SEO Meta Description</span>
                            <input type="text" name="meta_description" value="{{ $settings['meta_description'] ?? '' }}"/>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">Save Site Settings <span>→</span></button>
                    </div>
                </form>
            </div>

            <div class="dash-surface">
                <h3>Factory Reset Option</h3>
                <p>If you ever want to restore all texts, articles, rates, and settings back to the initial DhakaFin defaults, click below:</p>
                <form method="POST" action="{{ route('admin.reset') }}" class="demo-form" onsubmit="return confirm('Restore all CMS settings and content to original defaults?')">
                    @csrf
                    <button type="submit" class="secondary-light">Restore Default DhakaFin Content</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
