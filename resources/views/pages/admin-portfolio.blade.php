@extends('layouts.app')

@section('title', 'Team & portfolio manager | DhakaFin')
@section('meta_description', 'Maintain team profiles, services and professional biographies.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Admin Studio" data-kind="admin_table" data-page="admin-portfolio"@endsection

@section('content')
@php
    $editId = isset($request) ? (int) $request->query('edit', 0) : (int) request('edit', 0);
    $editingMember = collect($cms['team'] ?? [])->firstWhere('id', $editId);
@endphp
<section class="v2-hero v2-hero-compact" data-area="Admin Studio">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'admin-dashboard') }}">ADMIN STUDIO</a><span>—</span>
                TEAM & PORTFOLIO MANAGER
            </div>
            <div class="v2-overline"><i></i>100% NO-CODE WEBSITE CONTROL <span>·</span> ADMIN STUDIO</div>
            <h1>Team profiles &amp;<br/><em>advisory specialists.</em></h1>
            <p>Add, edit, or remove team members displayed on the Team directory and Professional Profile pages.</p>
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
                    <div class="section-kicker">ADMIN STUDIO / TEAM &amp; PORTFOLIO</div>
                    <h2>Team &amp; biography manager</h2>
                    <p>Profiles saved here appear live on the Our Team and Professional Profile pages.</p>
                </div>
                <a class="primary-btn" href="{{ route('pages.show', 'team') }}">View Team Page ↗</a>
            </div>

            <div class="dash-surface">
                <h3>{{ $editingMember ? 'Edit Profile: ' . $editingMember['name'] : 'Add Team Member / Specialist' }}</h3>
                <form method="POST" action="{{ route('admin.portfolio.save') }}" class="demo-form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $editingMember['id'] ?? 0 }}"/>
                    <div class="form-grid">
                        <label class="form-field">
                            <span>Full Name or Role Title</span>
                            <input type="text" name="name" value="{{ $editingMember['name'] ?? '' }}" required placeholder="e.g., Sohan Rahman, FCA — Managing Partner"/>
                        </label>
                        <label class="form-field">
                            <span>Practice Area / Kicker</span>
                            <input type="text" name="kicker" value="{{ $editingMember['kicker'] ?? 'TAX ADVISORY' }}" required placeholder="TAX ADVISORY / ASSURANCE / VIRTUAL CFO"/>
                        </label>
                        <label class="form-field">
                            <span>Card Summary</span>
                            <input type="text" name="summary" value="{{ $editingMember['summary'] ?? '' }}" required placeholder="Short summary displayed on the Team card..."/>
                        </label>
                        <label class="form-field">
                            <span>Expertise Pills (comma-separated)</span>
                            <input type="text" name="tags" value="{{ isset($editingMember['tags']) ? implode(', ', $editingMember['tags']) : 'Qualifications, Professional focus, Experience' }}"/>
                        </label>
                        <label class="form-field" style="grid-column:1/-1">
                            <span>Full Professional Biography</span>
                            <textarea name="bio" rows="3" required placeholder="Detailed biography shown on the individual profile page...">{{ $editingMember['bio'] ?? '' }}</textarea>
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="primary-btn">{{ $editingMember ? 'Update Profile' : 'Add Team Member' }} <span>→</span></button>
                    </div>
                </form>
            </div>

            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name / Role</th>
                            <th>Practice Area</th>
                            <th>Summary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cms['team'] ?? [] as $member)
                            <tr>
                                <td><b>{{ $member['code'] }}</b></td>
                                <td><b>{{ $member['name'] }}</b></td>
                                <td>{{ $member['kicker'] }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($member['summary'], 65) }}</td>
                                <td>
                                    <a href="{{ route('pages.show', 'admin-portfolio') }}?edit={{ $member['id'] }}" style="color:#07805b;font-weight:800;margin-right:10px">Edit</a>
                                    <a href="{{ route('pages.show', 'team-profile') }}?id={{ $member['id'] }}" style="color:#07805b;font-weight:800;margin-right:10px">Profile ↗</a>
                                    <form method="POST" action="{{ route('admin.portfolio.delete') }}" style="display:inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $member['id'] }}"/>
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
