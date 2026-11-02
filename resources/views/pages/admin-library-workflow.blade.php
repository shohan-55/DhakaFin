@extends('layouts.app')

@section('title', 'Library workflow | DhakaFin')
@section('meta_description', 'Editorial review stages for tax research content and legal library items.')
@section('body_class', 'v2-page')
@section('body_attrs')data-group="Admin Studio" data-kind="admin_dashboard" data-page="admin-library-workflow"@endsection

@section('content')
<section class="v2-hero v2-hero-compact" data-area="Admin Studio">
    <div class="site-container v2-hero-inner">
        <div class="v2-hero-copy">
            <div class="v2-crumb">
                <a href="{{ route('home') }}">HOME</a><span>—</span>
                <a href="{{ route('pages.show', 'admin-dashboard') }}">ADMIN STUDIO</a><span>—</span>
                LIBRARY WORKFLOW
            </div>
            <div class="v2-overline"><i></i>100% NO-CODE WEBSITE CONTROL <span>·</span> ADMIN STUDIO</div>
            <h1>Editorial review &amp;<br/><em>verification stages.</em></h1>
            <p>Track and toggle publication status across all legal library chapters, circulars, and articles.</p>
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
                    <div class="section-kicker">ADMIN STUDIO / LIBRARY WORKFLOW</div>
                    <h2>Legal content verification pipeline</h2>
                    <p>Toggle workflow status between Draft, In Review, and Published for statutory provisions.</p>
                </div>
                <a class="primary-btn" href="{{ route('pages.show', 'admin-library') }}">Add New Provision ↗</a>
            </div>
            <div class="data-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Current Stage</th>
                            <th>Update Workflow Stage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cms['library'] ?? [] as $item)
                            <tr>
                                <td><b>{{ $item['code'] }}</b></td>
                                <td><b>{{ $item['title'] }}</b></td>
                                <td>{{ $item['type'] }}</td>
                                <td><span class="table-status">{{ $item['status'] ?? 'Published' }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.workflow.save') }}" style="display:inline-flex;gap:8px;align-items:center">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item['id'] }}"/>
                                        <select name="status" style="min-height:36px;padding:6px 10px">
                                            @foreach (['Published', 'In Review', 'Draft'] as $st)
                                                <option value="{{ $st }}" {{ ($item['status'] ?? 'Published') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit">Update</button>
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
