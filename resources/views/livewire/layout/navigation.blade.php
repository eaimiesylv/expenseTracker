 <div class="flex flex-col h-full">
    <div class="flex items-center gap-2 px-6 py-5">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:var(--emerald)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 4h16M4 12h10M4 20h16" stroke="white" stroke-width="2.4" stroke-linecap="round"/></svg>
        </span>
        <span class="font-display text-lg font-bold tracking-tight">LedgerFlow</span>
    </div>

    <nav class="flex-1 space-y-1 px-3 py-2">
        <a href="{{ route('dashboard') }}" class="side-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V10.5Z"/></svg>
            Dashboard
        </a>
        <a href="{{ route('analytics') }}" class="side-link {{ request()->routeIs('analytics') ? 'active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 19V10"/><path d="M12 19V5"/><path d="M19 19v-7"/><path d="M3 19h18"/></svg>
            Analytics
        </a>

        <a href="{{ route('budgets') }}" class="side-link {{ request()->routeIs('budgets') ? 'active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M8 4v0"/></svg>
            Budgets
        </a>
        <a href="{{ route('expenses') }}" class="side-link {{ request()->routeIs('expenses') ? 'active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v18M17 7.5c0-1.9-2.2-2.5-5-2.5s-5 1-5 2.7 2.2 2.4 5 2.8 5 1.1 5 2.8-2.2 2.7-5 2.7-5-.6-5-2.5"/></svg>
            Expenses
        </a>
        <a href="{{ route('bills') }}" class="side-link {{ request()->routeIs('bills') ? 'active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h13l3 4v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Z"/><path d="M8 11h8M8 15h5"/></svg>
            Bills &amp; Splits
        </a>
        <a href="{{ route('groups') }}" class="side-link {{ request()->routeIs('groups') ? 'active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.5"/><path d="M3.5 19c.7-3 3-5 5.5-5s4.8 2 5.5 5M14.5 19c.3-2 1.6-3.6 3.3-4.3"/></svg>
            Groups
        </a>

        <a href="{{ route('savings') }}" class="side-link {{ request()->routeIs('savings') ? 'active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3c-2 0-4 1-5 3-1 2 0 4 2 5 2 1 3 3 3 5 0 2 2 3 4 3s4-1 4-3c0-2-1-4-3-5-2-1-3-3-3-5 0-1-1-3-2-3z"/></svg>
            Record savings
        </a>

         <a href="{{ route('notifications') }}" class="side-link {{ request()->routeIs('notifications') ? 'active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 8a6 6 0 1 1 12 0c0 4 1.5 5.5 2 6H4c.5-.5 2-2 2-6Z"/><path d="M10 18a2 2 0 0 0 4 0"/></svg>
            Notifications
        </a>
        
        <div class="my-3 border-t border-slate-100"></div>

        <a href="{{ route('settings') }}" class="side-link {{ request()->routeIs('settings') ? 'active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.9.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.9-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.9V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z"/></svg>
            Settings
        </a>
    </nav>
</div>