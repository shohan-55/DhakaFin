@extends('layouts.app')

@section('title', 'Homepage manager | DhakaFin')
@section('meta_description', 'Edit homepage hero copy, featured tools, and any page content without coding.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Admin Studio" data-kind="admin_form" data-page="admin-homepage"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Admin Studio">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'admin-dashboard') }}">ADMIN STUDIO</a><span>—</span>
                HOMEPAGE MANAGER
            </div>
            <div class="v2-overline"><i></i>100% NO-CODE WEBSITE CONTROL <span>·</span> ADMIN STUDIO</div>
            <h1>Homepage &amp; page<br/><em>content manager.</em></h1>
            <p>Edit homepage hero copy, CTA buttons, or customize the heading and text of any page on the website without coding.</p>
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
                    <div class="section-kicker">ADMIN STUDIO / HOMEPAGE MANAGER</div>
                    <h2>Homepage content &amp; hero editor</h2>
                    <p>Update the main landing page texts below, or click "Launch Live On-Page Editor" to click and edit any text directly on the page.</p>
                </div>
                <a class="primary-btn" href="{{ route('home') }}?edit=1">✦ Live Visual Editor ↗</a>
            </div>

            <div class="dash-surface">
                <h3>01 / Homepage Hero &amp; Section Copy</h3>
                <form method="POST" action="{{ route('admin.homepage.save') }}" class="demo-form">
                    @csrf
                    <div class="form-grid">
                        <label class="form-field">
                            <span>Hero Eyebrow</span>
                            <input type="text" name="eyebrow" value="{{ $cms['homepage']['eyebrow'] ?? '' }}"/>
                        </label>
                        <label class="form-field">
                            <span>Primary CTA Button Text</span>
                            <input type="text" name="cta_primary_text" value="{{ $cms['homepage']['cta_primary_text'] ?? '' }}"/>
                        </label>
                        <label class="form-field">
                            <span>Secondary CTA Button Text</span>
                            <input type="text" name="cta_secondary_text" value="{{ $cms['homepage']['cta_secondary_text'] ?? '' }}"/>
                        </label>
                        <label class="form-field">
                            <span>Hero Lead Paragraph</span>
                            <input type="text" name="hero_lead" value="{{ $cms['homepage']['hero_lead'] ?? '' }}"/>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">Save Homepage Content <span>→</span></button>
                    </div>
                </form>
            </div>

            <div class="dash-surface">
                <h3>02 / Universal Page Hero &amp; Text Override (Edit Any of the 72 Pages)</h3>
                <p>Select any page on the website to update its Hero Heading, Hero Subtitle, or replace any specific text phrase without touching code.</p>
                <form method="POST" action="{{ route('admin.page-override.save') }}" class="demo-form">
                    @csrf
                    <div class="form-grid">
                        <label class="form-field">
                            <span>Select target page</span>
                            <select name="slug">
                                <option value="home">Homepage (home)</option>
                                @foreach (config('dhakafin.pages', []) as $pSlug => $pMeta)
                                    <option value="{{ $pSlug }}">{{ $pMeta['title'] ?? $pSlug }} ({{ $pSlug }})</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="form-field">
                            <span>New Hero Heading (optional, supports &lt;br/&gt; &amp; &lt;em&gt;)</span>
                            <input type="text" name="hero_title" placeholder="e.g., A fresh perspective<br/><em>on finance.</em>"/>
                        </label>
                        <label class="form-field">
                            <span>New Hero Subtitle / Lead Paragraph (optional)</span>
                            <input type="text" name="hero_lead" placeholder="New subtitle text for this page..."/>
                        </label>
                        <label class="form-field">
                            <span>Find Text on Page (optional text replacement)</span>
                            <input type="text" name="find_text" placeholder="Exact existing text to replace..."/>
                        </label>
                        <label class="form-field">
                            <span>Replace With Text</span>
                            <input type="text" name="replace_text" placeholder="New text to display instead..."/>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">Publish Page Update <span>→</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
