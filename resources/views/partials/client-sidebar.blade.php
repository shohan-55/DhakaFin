@php
    $currentSlug = $slug ?? 'client-dashboard';
    $clientNav = [
        ['slug' => 'client-dashboard', 'icon' => '▣', 'label' => 'Client overview', 'badge' => '→'],
        ['slug' => 'documents', 'icon' => '▤', 'label' => 'Document vault', 'badge' => count($cms['documents'] ?? [])],
        ['slug' => 'mushak-list', 'icon' => '▧', 'label' => 'Mushak invoices', 'badge' => count($cms['invoices'] ?? [])],
        ['slug' => 'mushak-detail', 'icon' => '▧', 'label' => 'Invoice details', 'badge' => '→'],
        ['slug' => 'submissions', 'icon' => '◷', 'label' => 'Tax submissions', 'badge' => count($cms['submissions'] ?? [])],
        ['slug' => 'reports', 'icon' => '▥', 'label' => 'Reports & analytics', 'badge' => '→'],
        ['slug' => 'notifications', 'icon' => '◉', 'label' => 'Notifications', 'badge' => count($cms['notifications'] ?? [])],
        ['slug' => 'account-settings', 'icon' => '⚙', 'label' => 'Account settings', 'badge' => '→'],
    ];
@endphp
<aside class="portal-side">
    <div class="side-overline">DHAKAFIN / CLIENT WORKSPACE</div>
    <a class="side-brand" href="{{ route('home') }}">df. <span>DhakaFin</span></a>
    <div class="portal-user">
        <div>CL</div>
        <span>
            <b>{{ $cms['admin_credentials']['client_name'] ?? 'DhakaFin Client' }}</b>
            <small>{{ $cms['admin_credentials']['client_org'] ?? 'Client Portal' }}</small>
        </span>
    </div>
    <nav aria-label="Client Workspace navigation">
        @foreach ($clientNav as $item)
            <a href="{{ route('pages.show', $item['slug']) }}" class="{{ $currentSlug === $item['slug'] ? 'selected' : '' }}">
                <span>{{ $item['icon'] }}</span>
                {{ $item['label'] }}
                <b>{{ $item['badge'] }}</b>
            </a>
        @endforeach
        <a href="{{ route('pages.show', 'admin-dashboard') }}">
            <span>⚙</span> Admin Studio <b>↗</b>
        </a>
    </nav>
    <div class="side-foot">
        Connected with DhakaFin Digital Tools, Mushak 6.3 Invoicing, and Tax Advisory.
    </div>
</aside>
