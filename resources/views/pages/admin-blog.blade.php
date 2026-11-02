@extends('layouts.app')

@section('title', 'Blog manager | DhakaFin')
@section('meta_description', 'Create, organise and publish articles for DhakaFin Insights.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Admin Studio" data-kind="admin_table" data-page="admin-blog"@endsection

@section('content')
@php
    $editId = isset($request) ? (int) $request->query('edit', 0) : (int) request('edit', 0);
    $editingArticle = collect($cms['articles'] ?? [])->firstWhere('id', $editId);
@endphp
<section class="v2-hero v2-hero-compact" data-area="Admin Studio">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'admin-dashboard') }}">ADMIN STUDIO</a><span>—</span>
                BLOG MANAGER
            </div>
            <div class="v2-overline"><i></i>100% NO-CODE WEBSITE CONTROL <span>·</span> ADMIN STUDIO</div>
            <h1>Insights &amp; articles<br/><em>publishing studio.</em></h1>
            <p>Create, edit, or remove articles displayed on the Insights page and Article Detail view.</p>
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
                    <div class="section-kicker">ADMIN STUDIO / BLOG MANAGER</div>
                    <h2>{{ $editingArticle ? 'Edit Article #' . $editingArticle['id'] : 'Publish or Edit Insights Articles' }}</h2>
                    <p>Articles published here appear immediately on the public Insights page with full reading views.</p>
                </div>
                <a class="primary-btn" href="{{ route('pages.show', 'insights') }}">View Insights Page ↗</a>
            </div>

            <div class="dash-surface">
                <h3>{{ $editingArticle ? 'Update Article: ' . $editingArticle['title'] : 'Create New Article' }}</h3>
                <form method="POST" action="{{ route('admin.blog.save') }}" class="demo-form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $editingArticle['id'] ?? 0 }}"/>
                    <div class="form-grid">
                        <label class="form-field">
                            <span>Article Title</span>
                            <input type="text" name="title" value="{{ $editingArticle['title'] ?? '' }}" required placeholder="e.g., Corporate Tax Planning for FY 2026–2027"/>
                        </label>
                        <label class="form-field">
                            <span>Category Kicker</span>
                            <input type="text" name="category" value="{{ $editingArticle['category'] ?? 'TAX & COMPLIANCE' }}" required placeholder="TAX & COMPLIANCE / VAT / ACCOUNTING"/>
                        </label>
                        <label class="form-field">
                            <span>Card Summary</span>
                            <input type="text" name="summary" value="{{ $editingArticle['summary'] ?? '' }}" required placeholder="Short summary shown on the Insights card..."/>
                        </label>
                        <label class="form-field">
                            <span>Card Visual Style</span>
                            <select name="art_class">
                                @foreach (['art-0' => 'Emerald Deep (art-0)', 'art-1' => 'Dark Forest (art-1)', 'art-2' => 'Soft Mint (art-2)', 'art-3' => 'Rich Green (art-3)'] as $cls => $lbl)
                                    <option value="{{ $cls }}" {{ ($editingArticle['art_class'] ?? '') === $cls ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="form-field">
                            <span>Opening Paragraph</span>
                            <textarea name="body_p1" rows="3" required placeholder="Opening paragraph of the article...">{{ $editingArticle['body_p1'] ?? '' }}</textarea>
                        </label>
                        <label class="form-field">
                            <span>Section 1 Subheading &amp; Body</span>
                            <input type="text" name="body_h1" value="{{ $editingArticle['body_h1'] ?? 'Clarity before complexity' }}" placeholder="Subheading 1"/>
                            <textarea name="body_p2" rows="3" placeholder="Section 1 body text...">{{ $editingArticle['body_p2'] ?? '' }}</textarea>
                        </label>
                        <label class="form-field">
                            <span>Section 2 Subheading &amp; Body</span>
                            <input type="text" name="body_h2" value="{{ $editingArticle['body_h2'] ?? 'Thoughtful next steps' }}" placeholder="Subheading 2"/>
                            <textarea name="body_p3" rows="3" placeholder="Section 2 body text...">{{ $editingArticle['body_p3'] ?? '' }}</textarea>
                        </label>
                        <label class="form-field">
                            <span>Publication Status</span>
                            <select name="status">
                                <option value="Published" {{ ($editingArticle['status'] ?? '') === 'Published' ? 'selected' : '' }}>Published</option>
                                <option value="Draft" {{ ($editingArticle['status'] ?? '') === 'Draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">{{ $editingArticle ? 'Update Article' : 'Publish Article' }} <span>→</span></button>
                        @if ($editingArticle)
                            <a href="{{ route('pages.show', 'admin-blog') }}" class="secondary-light">Cancel Edit</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Summary</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cms['articles'] ?? [] as $art)
                            <tr>
                                <td><b>{{ $art['code'] }}</b></td>
                                <td><b>{{ $art['title'] }}</b></td>
                                <td>{{ $art['category'] }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($art['summary'], 60) }}</td>
                                <td><span class="table-status">{{ $art['status'] ?? 'Published' }}</span></td>
                                <td>
                                    <a href="{{ route('pages.show', 'admin-blog') }}?edit={{ $art['id'] }}" style="color:#07805b;font-weight:800;margin-right:10px">Edit</a>
                                    <a href="{{ route('pages.show', 'insight-detail') }}?id={{ $art['id'] }}" style="color:#07805b;font-weight:800;margin-right:10px">View ↗</a>
                                    <form method="POST" action="{{ route('admin.blog.delete') }}" style="display:inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $art['id'] }}"/>
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
