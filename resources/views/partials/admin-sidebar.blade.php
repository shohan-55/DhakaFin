@php
    $currentSlug = $slug ?? 'admin-dashboard';
    $unreadCount = collect($cms['enquiries'] ?? [])->where('status', 'Unread')->count();
    $adminNav = [
        ['slug' => 'admin-dashboard', 'icon' => '▣', 'label' => 'Admin overview', 'badge' => '→'],
        ['slug' => 'admin-homepage', 'icon' => '◆', 'label' => 'Homepage manager', 'badge' => '→'],
        ['slug' => 'admin-blog', 'icon' => '✎', 'label' => 'Blog manager', 'badge' => count($cms['articles'] ?? [])],
        ['slug' => 'admin-deadlines', 'icon' => '◷', 'label' => 'Deadline manager', 'badge' => count($cms['deadlines'] ?? [])],
        ['slug' => 'admin-library', 'icon' => '▤', 'label' => 'Legal library manager', 'badge' => count($cms['library'] ?? [])],
        ['slug' => 'admin-tax-rates', 'icon' => '#', 'label' => 'Tax rate manager', 'badge' => '→'],
        ['slug' => 'admin-portfolio', 'icon' => '♙', 'label' => 'Team & portfolio', 'badge' => count($cms['team'] ?? [])],
        ['slug' => 'admin-messages', 'icon' => '✉', 'label' => 'Messages', 'badge' => $unreadCount > 0 ? $unreadCount . ' new' : count($cms['enquiries'] ?? [])],
        ['slug' => 'admin-footer', 'icon' => '▤', 'label' => 'Footer manager', 'badge' => '→'],
        ['slug' => 'admin-settings', 'icon' => '⚙', 'label' => 'Site & page settings', 'badge' => '→'],
        ['slug' => 'admin-auth', 'icon' => '◈', 'label' => 'Access settings', 'badge' => '→'],
        ['slug' => 'admin-library-workflow', 'icon' => '⇢', 'label' => 'Library workflow', 'badge' => '→'],
        ['slug' => 'admin-library-analytics', 'icon' => '▥', 'label' => 'Library analytics', 'badge' => '→'],
    ];
@endphp
<aside class="portal-side">
    <div class="side-overline">DHAKAFIN / ADMIN STUDIO</div>
    <a class="side-brand" href="{{ route('home') }}">df. <span>DhakaFin</span></a>
    <div class="portal-user">
        <div>AD</div>
        <div>
            <b>{{ $cms['admin_credentials']['name'] ?? 'Administrator' }}</b>
            <small>100% No-Code Studio</small>
        </div>
    </div>
    <nav aria-label="Admin Studio navigation">
        @foreach ($adminNav as $item)
            <a href="{{ route('pages.show', $item['slug']) }}" class="{{ $currentSlug === $item['slug'] ? 'selected' : '' }}">
                <span>{{ $item['icon'] }}</span>
                {{ $item['label'] }}
                <b>{{ $item['badge'] }}</b>
            </a>
        @endforeach
        <a href="{{ route('pages.show', 'client-dashboard') }}">
            <span>⇄</span> Client Workspace <b>↗</b>
        </a>
        <a href="{{ route('home') }}?edit=1">
            <span>✦</span> Live On-Page Editor <b>↗</b>
        </a>
    </nav>
    <div class="side-foot">
        All changes made in Admin Studio update the live DhakaFin website immediately without coding.
    </div>
</aside>
