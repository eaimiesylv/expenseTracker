<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4" x-data="{}">
            <div>
                <h2 class="font-display text-xl font-semibold leading-tight text-slate-900">Notifications</h2>
                <p class="mt-0.5 text-sm text-slate-500">Stay updated with your budgets, expenses, groups and bills.</p>
            </div>
            <div class="hidden items-center gap-2 sm:flex">
                <button type="button" @click="markAllRead()" class="rounded-full border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:border-slate-300">Mark All Read</button>
                <button type="button" @click="$store.settingsDrawer.open = true" class="rounded-full bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700">Notification Settings</button>
            </div>
        </div>
    </x-slot>

    <style>
        [x-cloak] { display: none !important; }
        .b-card{ background:#fff; border:1px solid #E5E9F0; border-radius:20px; box-shadow: 0 1px 2px rgba(15,23,42,0.03), 0 10px 28px -14px rgba(15,23,42,0.10); }
        .badge{ display:inline-flex; align-items:center; border-radius:9999px; padding:.2rem .6rem; font-size:.7rem; font-weight:600; }
        .status-dot{ height:.5rem; width:.5rem; border-radius:9999px; display:inline-block; }
        .field-label{ font-size:.75rem; font-weight:600; color:#475569; }
        .field-input{ margin-top:.375rem; width:100%; border-radius:.75rem; border:1px solid #E2E8F0; padding:.625rem .875rem; font-size:.875rem; }
        .field-input:focus{ outline:none; border-color:#60A5FA; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .segbtn{ border-radius:.75rem; border:1px solid #E2E8F0; padding:.5rem .75rem; font-size:.75rem; font-weight:600; color:#64748B; }
        .segbtn.active{ border-color:#2563EB; background:#EFF6FF; color:#1D4ED8; }
        .toggle{ position:relative; width:2.5rem; height:1.4rem; border-radius:9999px; background:#E2E8F0; transition:background .15s ease; cursor:pointer; flex-shrink:0; }
        .toggle.on{ background:#2563EB; }
        .toggle span{ position:absolute; top:2px; left:2px; height:1.1rem; width:1.1rem; border-radius:9999px; background:#fff; transition:transform .15s ease; }
        .toggle.on span{ transform:translateX(1.1rem); }
        .skeleton{ background: linear-gradient(90deg,#EEF1F6 25%,#F6F8FA 37%,#EEF1F6 63%); background-size:400% 100%; animation: shimmer 1.4s ease infinite; border-radius:16px; }
        @keyframes shimmer{ 0%{ background-position:100% 50%;} 100%{ background-position:0 50%;} }
        .swipe-bg{ position:absolute; inset:0; border-radius:20px; display:flex; align-items:center; font-size:.75rem; font-weight:700; color:#fff; padding:0 1.25rem; }
    </style>

    {{-- ============================================================
         Fallback sample data — replace with a real Livewire component
         (e.g. App\Livewire\Notifications\Index) passing $notifications,
         $summary as public properties, and hook a real broadcast
         channel (Echo/Pusher/Reverb) for the "real-time" requirement.
    ============================================================ --}}
    @php
        $notifications = $notifications ?? collect([
            (object)['id' => 1, 'category' => 'Budgets', 'icon' => '💰', 'title' => 'Contribution Received', 'description' => 'John has contributed ₦20,000 to Family Monthly Budget.', 'timestamp' => '2 minutes ago', 'date' => now()->toDateString(), 'day' => 'Today', 'status' => 'Unread', 'priority' => 'Medium', 'related_type' => 'Budget', 'related_name' => 'Family Monthly Budget', 'deep_link' => '/budgets'],
            (object)['id' => 2, 'category' => 'Groups', 'icon' => '👨‍👩‍👧', 'title' => 'New Member Joined', 'description' => 'Mary accepted your invitation and joined Family Group.', 'timestamp' => 'Today, 9:14 AM', 'date' => now()->toDateString(), 'day' => 'Today', 'status' => 'Read', 'priority' => 'Low', 'related_type' => 'Group', 'related_name' => 'Family', 'deep_link' => '/groups'],
            (object)['id' => 3, 'category' => 'Bills', 'icon' => '🧾', 'title' => 'Bill Overdue', 'description' => 'Your share of Electricity Bill is ₦15,000. Payment is now overdue.', 'timestamp' => 'Today, 7:40 AM', 'date' => now()->toDateString(), 'day' => 'Today', 'status' => 'Unread', 'priority' => 'High', 'related_type' => 'Bill', 'related_name' => 'Electricity Bill', 'deep_link' => '/bills'],
            (object)['id' => 4, 'category' => 'Expenses', 'icon' => '📄', 'title' => 'Expense Approved', 'description' => 'Your expense "School Fees" — ₦25,000 — has been approved by Emmanuel.', 'timestamp' => 'Yesterday, 4:20 PM', 'date' => now()->subDay()->toDateString(), 'day' => 'Yesterday', 'status' => 'Read', 'priority' => 'Medium', 'related_type' => 'Expense', 'related_name' => 'School Fees', 'deep_link' => '/expenses'],
            (object)['id' => 5, 'category' => 'Savings', 'icon' => '🏦', 'title' => 'Savings Goal Reached', 'description' => 'Your "New Laptop" savings plan has reached its target of ₦800,000.', 'timestamp' => 'Yesterday, 11:05 AM', 'date' => now()->subDay()->toDateString(), 'day' => 'Yesterday', 'status' => 'Unread', 'priority' => 'Medium', 'related_type' => 'Savings Plan', 'related_name' => 'New Laptop', 'deep_link' => '/savings'],
            (object)['id' => 6, 'category' => 'Budgets', 'icon' => '⚠️', 'title' => 'Budget Exceeded', 'description' => 'Your "Vacation Budget" has exceeded its limit by ₦12,000.', 'timestamp' => 'Yesterday, 8:00 AM', 'date' => now()->subDay()->toDateString(), 'day' => 'Yesterday', 'status' => 'Read', 'priority' => 'High', 'related_type' => 'Budget', 'related_name' => 'Vacation Budget', 'deep_link' => '/budgets'],
            (object)['id' => 7, 'category' => 'Invitations', 'icon' => '✉️', 'title' => 'Pending Invitation', 'description' => 'Your invitation to Grandma for Family Group is still pending.', 'timestamp' => '2 days ago', 'date' => now()->subDays(2)->toDateString(), 'day' => '2 days ago', 'status' => 'Read', 'priority' => 'Low', 'related_type' => 'Group', 'related_name' => 'Family', 'deep_link' => '/groups'],
            (object)['id' => 8, 'category' => 'Reminders', 'icon' => '⏰', 'title' => 'Budget Ending Soon', 'description' => '"Family Monthly Budget" ends in 3 days.', 'timestamp' => '2 days ago', 'date' => now()->subDays(2)->toDateString(), 'day' => '2 days ago', 'status' => 'Archived', 'priority' => 'Medium', 'related_type' => 'Budget', 'related_name' => 'Family Monthly Budget', 'deep_link' => '/budgets'],
            (object)['id' => 9, 'category' => 'System', 'icon' => '🔒', 'title' => 'Password Changed', 'description' => 'Your account password was changed successfully.', 'timestamp' => '3 days ago', 'date' => now()->subDays(3)->toDateString(), 'day' => '3 days ago', 'status' => 'Read', 'priority' => 'Low', 'related_type' => 'Account', 'related_name' => 'Security', 'deep_link' => '/settings'],
            (object)['id' => 10, 'category' => 'Bills', 'icon' => '🧾', 'title' => 'Payment Partially Received', 'description' => 'Mother paid ₦10,000 toward Family Rent. ₦150,000 still outstanding.', 'timestamp' => '3 days ago', 'date' => now()->subDays(3)->toDateString(), 'day' => '3 days ago', 'status' => 'Read', 'priority' => 'Medium', 'related_type' => 'Bill', 'related_name' => 'Family Rent', 'deep_link' => '/bills'],
            (object)['id' => 11, 'category' => 'Approvals', 'icon' => '✅', 'title' => 'Budget Request Approved', 'description' => 'Your request for School Books — ₦25,000 — was approved by Father.', 'timestamp' => '3 days ago', 'date' => now()->subDays(3)->toDateString(), 'day' => '3 days ago', 'status' => 'Read', 'priority' => 'Low', 'related_type' => 'Budget', 'related_name' => 'Family Monthly Budget', 'deep_link' => '/budgets'],
        ]);

        $total = $notifications->count();
        $unread = $notifications->where('status', 'Unread')->count();
        $todayCount = $notifications->where('day', 'Today')->count();
        $thisWeek = $notifications->filter(fn ($n) => \Carbon\Carbon::parse($n->date)->greaterThanOrEqualTo(now()->startOfWeek()))->count();
        $highPriority = $notifications->where('priority', 'High')->count();
        $archived = $notifications->where('status', 'Archived')->count();

        $summaryCards = [
            ['label' => 'Total Notifications', 'value' => $total, 'trend' => '+4 today', 'up' => true, 'icon' => 'bell'],
            ['label' => 'Unread', 'value' => $unread, 'trend' => '↑ 3 today', 'up' => true, 'icon' => 'dot'],
            ['label' => 'Today', 'value' => $todayCount, 'trend' => 'New activity', 'up' => null, 'icon' => 'calendar'],
            ['label' => 'This Week', 'value' => $thisWeek, 'trend' => 'Across all categories', 'up' => null, 'icon' => 'list'],
            ['label' => 'High Priority', 'value' => $highPriority, 'trend' => 'Needs attention', 'up' => false, 'icon' => 'alert'],
            ['label' => 'Archived', 'value' => $archived, 'trend' => 'Stored for reference', 'up' => null, 'icon' => 'archive'],
        ];

        $categories = ['Groups', 'Budgets', 'Expenses', 'Bills', 'Savings', 'Reminders', 'Invitations', 'Approvals', 'System'];
        $priorityTone = ['High' => 'bg-rose-50 text-rose-700', 'Medium' => 'bg-amber-50 text-amber-700', 'Low' => 'bg-blue-50 text-blue-700'];
        $priorityDot = ['High' => 'bg-rose-500', 'Medium' => 'bg-amber-500', 'Low' => 'bg-blue-500'];

        $smartReminders = $smartReminders ?? [
            "You haven't recorded any expenses this week.",
            "Your monthly savings hasn't been updated.",
            'Electricity Bill is due tomorrow.',
            "John hasn't contributed to Family Budget.",
            'Family Monthly Budget reaches its end date in 5 days.',
            'Christmas Savings is 90% complete.',
        ];
    @endphp

    <div x-data="notificationsPage()" x-init="init()" x-cloak class="relative mx-auto max-w-7xl space-y-8 pb-24">

        {{-- Mobile floating action --}}
        <button type="button" @click="$store.settingsDrawer.open = true"
                class="fixed bottom-6 right-6 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-300 sm:hidden">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2a4 4 0 0 0-4 4v3.3c0 .6-.2 1.2-.6 1.7L6 13.5a1 1 0 0 0 .8 1.6h10.4a1 1 0 0 0 .8-1.6l-1.4-2.5c-.4-.5-.6-1.1-.6-1.7V6a4 4 0 0 0-4-4Z"/><path d="M9.5 18a2.5 2.5 0 0 0 5 0"/></svg>
        </button>

        {{-- Mobile quick actions bar --}}
        <div class="flex gap-2 sm:hidden">
            <button type="button" @click="markAllRead()" class="flex-1 rounded-full border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600">Mark All Read</button>
        </div>

        @if ($notifications->isEmpty())
            <div class="b-card flex flex-col items-center gap-3 px-6 py-20 text-center">
                <div class="relative flex h-20 w-20 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 2a4 4 0 0 0-4 4v3.3c0 .6-.2 1.2-.6 1.7L6 13.5a1 1 0 0 0 .8 1.6h10.4a1 1 0 0 0 .8-1.6l-1.4-2.5c-.4-.5-.6-1.1-.6-1.7V6a4 4 0 0 0-4-4Z"/><path d="M9.5 18a2.5 2.5 0 0 0 5 0"/></svg>
                    <span class="absolute -right-1 -top-1 flex h-7 w-7 items-center justify-center rounded-full bg-emerald-500 text-white"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m5 13 4 4L19 7"/></svg></span>
                </div>
                <p class="font-display text-lg font-semibold text-slate-900">You're all caught up!</p>
                <p class="text-sm text-slate-500">No new notifications. Everything looks good.</p>
            </div>
        @else

            {{-- Summary cards --}}
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($summaryCards as $card)
                    <div class="b-card p-5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            @switch($card['icon'])
                                @case('bell')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2a4 4 0 0 0-4 4v3.3c0 .6-.2 1.2-.6 1.7L6 13.5a1 1 0 0 0 .8 1.6h10.4a1 1 0 0 0 .8-1.6l-1.4-2.5c-.4-.5-.6-1.1-.6-1.7V6a4 4 0 0 0-4-4Z"/><path d="M9.5 18a2.5 2.5 0 0 0 5 0"/></svg>
                                    @break
                                @case('dot')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="6" fill="currentColor"/></svg>
                                    @break
                                @case('calendar')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
                                    @break
                                @case('list')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                    @break
                                @case('archive')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="5" rx="1"/><path d="M5 9v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9M10 13h4"/></svg>
                                    @break
                                @default
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10.3 3.9 1.8 18a1.5 1.5 0 0 0 1.3 2.2h17.8a1.5 1.5 0 0 0 1.3-2.2L13.7 3.9a1.5 1.5 0 0 0-2.6 0Z"/><path d="M12 9v4M12 17h.01"/></svg>
                            @endswitch
                        </span>
                        <p class="font-mono mt-4 text-2xl font-semibold text-slate-900">{{ $card['value'] }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-500">{{ $card['label'] }}</p>
                        @if (!is_null($card['up']))
                            <p class="mt-2 text-xs font-medium {{ $card['up'] ? 'text-emerald-600' : 'text-rose-600' }}">{{ $card['trend'] }}</p>
                        @else
                            <p class="mt-2 text-xs font-medium text-slate-400">{{ $card['trend'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Sticky filter toolbar --}}
            <div class="sticky top-[73px] z-30 -mx-2 rounded-2xl border border-slate-200 bg-white/95 p-3 backdrop-blur-md sm:mx-0">
                <div class="flex flex-wrap items-center gap-2">
                    <div class="relative flex-1 min-w-[180px]">
                        <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" x-model="filters.search" placeholder="Search title, user, group, budget, expense, bill..."
                               class="w-full rounded-full border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-4 text-sm focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    <select x-model="filters.duration" class="hidden rounded-full border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-600 sm:block">
                        <option>Today</option><option>Yesterday</option><option selected>This Week</option>
                        <option>Last Week</option><option>This Month</option><option>Last Month</option><option>Custom Date Range</option>
                    </select>
                    <button type="button" @click="mobileFiltersOpen = true" class="flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-600 sm:hidden">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
                        Filters
                    </button>
                </div>

                <div class="mt-3 hidden flex-wrap items-center gap-2 sm:flex">
                    <template x-for="opt in ['All','Unread','Read','Archived']" :key="opt">
                        <button type="button" @click="filters.status = opt" :class="filters.status === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                    </template>
                    <span class="mx-1 h-5 w-px bg-slate-200"></span>
                    <select x-model="filters.category" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">
                        <option value="">All categories</option>
                        @foreach ($categories as $c) <option>{{ $c }}</option> @endforeach
                    </select>
                    <template x-for="opt in ['All','High','Medium','Low']" :key="opt">
                        <button type="button" @click="filters.priority = opt" :class="filters.priority === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                    </template>

                    <div class="relative ml-auto" x-data="{ sortOpen: false }">
                        <button type="button" @click="sortOpen = !sortOpen" @click.away="sortOpen = false" class="flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M6 12h12M10 18h4"/></svg>
                            <span x-text="sortLabels[filters.sort]"></span>
                        </button>
                        <div x-show="sortOpen" x-transition class="absolute right-0 z-20 mt-2 w-40 rounded-xl border border-slate-100 bg-white p-1.5 shadow-lg">
                            <template x-for="(label, key) in sortLabels" :key="key">
                                <button type="button" @click="filters.sort = key; sortOpen = false" class="block w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-slate-600 hover:bg-slate-50" x-text="label"></button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mobile filter drawer --}}
            <template x-teleport="body">
                <div x-show="mobileFiltersOpen" x-cloak class="fixed inset-0 z-[90] sm:hidden">
                    <div class="absolute inset-0 bg-slate-900/40" @click="mobileFiltersOpen = false"></div>
                    <div x-show="mobileFiltersOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                         class="absolute inset-x-0 bottom-0 max-h-[85vh] overflow-y-auto rounded-t-3xl bg-white p-5">
                        <div class="mx-auto mb-4 h-1.5 w-10 rounded-full bg-slate-200"></div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-display text-base font-semibold text-slate-900">Filters</h3>
                            <button @click="mobileFiltersOpen = false" class="text-sm font-semibold text-blue-600">Done</button>
                        </div>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="field-label">Duration</label>
                                <select x-model="filters.duration" class="field-input">
                                    <option>Today</option><option>Yesterday</option><option selected>This Week</option>
                                    <option>Last Week</option><option>This Month</option><option>Last Month</option><option>Custom Date Range</option>
                                </select>
                            </div>
                            <div>
                                <label class="field-label">Status</label>
                                <div class="mt-1.5 grid grid-cols-4 gap-2">
                                    <template x-for="opt in ['All','Unread','Read','Archived']" :key="opt">
                                        <button type="button" @click="filters.status = opt" :class="filters.status === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <label class="field-label">Category</label>
                                <select x-model="filters.category" class="field-input">
                                    <option value="">All categories</option>
                                    @foreach ($categories as $c) <option>{{ $c }}</option> @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="field-label">Priority</label>
                                <div class="mt-1.5 grid grid-cols-4 gap-2">
                                    <template x-for="opt in ['All','High','Medium','Low']" :key="opt">
                                        <button type="button" @click="filters.priority = opt" :class="filters.priority === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <label class="field-label">Sort</label>
                                <select x-model="filters.sort" class="field-input">
                                    <template x-for="(label, key) in sortLabels" :key="key">
                                        <option :value="key" x-text="label"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Notification list + activity timeline --}}
            <div class="grid gap-6 xl:grid-cols-[1fr_300px]">
                <div x-show="loading" class="space-y-3">
                    <div class="skeleton h-24"></div><div class="skeleton h-24"></div><div class="skeleton h-24"></div>
                </div>

                <div x-show="!loading" class="space-y-6">
                    @foreach ($notifications->groupBy('day') as $day => $items)
                        <div>
                            <p class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-400">{{ $day }}</p>
                            <div class="space-y-3">
                                @foreach ($items as $n)
                                    <div class="relative overflow-hidden rounded-[20px]"
                                         data-name="{{ strtolower($n->title . ' ' . $n->description) }}"
                                         data-status="{{ strtolower($n->status) }}" data-category="{{ strtolower($n->category) }}" data-priority="{{ strtolower($n->priority) }}"
                                         x-show="isVisible($el)"
                                         x-data="swipeCard()">

                                        {{-- Swipe backgrounds (mobile) --}}
                                        <div class="swipe-bg justify-start bg-emerald-500" x-show="swipeX > 10">Mark Read</div>
                                        <div class="swipe-bg justify-end bg-amber-500" x-show="swipeX < -10">Archive</div>

                                        <div class="b-card relative p-5" :style="`transform: translateX(${swipeX}px); transition: ${touching ? 'none' : 'transform .2s ease'};`"
                                             @touchstart="onTouchStart($event)" @touchmove="onTouchMove($event)" @touchend="onTouchEnd($event, {{ $n->id }})"
                                             @contextmenu.prevent="$store.actionSheet.notification = @js($n); $store.actionSheet.open = true">

                                            <div class="flex items-start gap-3">
                                                <span class="mt-0.5 text-xl leading-none">{{ $n->icon }}</span>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        @if ($n->status === 'Unread')
                                                            <span class="status-dot bg-blue-500"></span>
                                                        @endif
                                                        <h3 class="font-display text-sm font-semibold text-slate-900">{{ $n->title }}</h3>
                                                        <span class="badge {{ $priorityTone[$n->priority] ?? '' }}">{{ $n->priority }}</span>
                                                    </div>
                                                    <p class="mt-1 text-sm text-slate-600">{{ $n->description }}</p>
                                                    <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-400">
                                                        <span>{{ $n->timestamp }}</span>
                                                        <span class="badge bg-slate-100 text-slate-500">{{ $n->category }}</span>
                                                        @if ($n->status === 'Archived')<span class="badge bg-slate-100 text-slate-500">Archived</span>@endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-3 flex items-center gap-2 border-t border-slate-100 pt-3">
                                                <button type="button" @click="$store.detailDrawer.notification=@js($n); $store.detailDrawer.open=true"
                                                        class="rounded-full border border-slate-200 px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300">View</button>
                                                <button type="button" @click="markRead({{ $n->id }})" class="rounded-full border border-slate-200 px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300">Mark as Read</button>
                                                <button type="button" @click="archive({{ $n->id }})" class="rounded-full border border-slate-200 px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300">Archive</button>
                                                <button type="button" @click="deleteNotification({{ $n->id }}, $el.closest('[data-name]'))" class="rounded-full border border-rose-200 px-3.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <p x-show="noResults()" class="b-card p-8 text-center text-sm text-slate-500">No notifications match your filters.</p>
                </div>

                {{-- Activity timeline (condensed) --}}
                <div class="space-y-6">
                    <div class="b-card p-5">
                        <h3 class="font-display text-sm font-semibold text-slate-900">Smart Reminders</h3>
                        <p class="mt-0.5 text-xs text-slate-500">Automatically generated based on your activity.</p>
                        <ul class="mt-3 space-y-2.5">
                            @foreach ($smartReminders as $reminder)
                                <li class="flex items-start gap-2 text-sm text-slate-600">
                                    <svg class="mt-0.5 shrink-0 text-amber-500" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a4 4 0 0 0-4 4v3.3c0 .6-.2 1.2-.6 1.7L6 13.5a1 1 0 0 0 .8 1.6h10.4a1 1 0 0 0 .8-1.6l-1.4-2.5c-.4-.5-.6-1.1-.6-1.7V6a4 4 0 0 0-4-4Z"/><path d="M9.5 18a2.5 2.5 0 0 0 5 0"/></svg>
                                    {{ $reminder }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="b-card p-5">
                        <h3 class="font-display text-sm font-semibold text-slate-900">Activity Timeline</h3>
                        <div class="mt-3 space-y-4">
                            @foreach ($notifications->groupBy('day') as $day => $items)
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $day }}</p>
                                    <div class="mt-1.5 space-y-1.5 border-l-2 border-slate-100 pl-3">
                                        @foreach ($items as $n)
                                            <p class="text-sm text-slate-600">{{ $n->title }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ============ Notification Detail Drawer ============ --}}
        <template x-teleport="body">
            <div x-show="$store.detailDrawer.open" x-cloak class="fixed inset-0 z-[95]" @keydown.escape.window="$store.detailDrawer.open = false">
                <div class="absolute inset-0 bg-slate-900/40" @click="$store.detailDrawer.open = false"></div>
                <div x-show="$store.detailDrawer.open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                     class="absolute inset-y-0 right-0 flex w-full max-w-sm flex-col overflow-y-auto bg-white shadow-2xl">
                    <template x-if="$store.detailDrawer.notification">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-2xl" x-text="$store.detailDrawer.notification.icon"></span>
                                    <h3 class="font-display text-base font-semibold text-slate-900" x-text="$store.detailDrawer.notification.title"></h3>
                                </div>
                                <button @click="$store.detailDrawer.open = false" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <p class="mt-4 text-sm text-slate-600" x-text="$store.detailDrawer.notification.description"></p>

                            <div class="mt-5 space-y-3 rounded-xl bg-slate-50 p-4 text-sm">
                                <div class="flex justify-between"><span class="text-slate-400">Related</span><span class="font-medium text-slate-800" x-text="$store.detailDrawer.notification.related_type + ': ' + $store.detailDrawer.notification.related_name"></span></div>
                                <div class="flex justify-between"><span class="text-slate-400">Category</span><span class="font-medium text-slate-800" x-text="$store.detailDrawer.notification.category"></span></div>
                                <div class="flex justify-between"><span class="text-slate-400">Priority</span><span class="font-medium text-slate-800" x-text="$store.detailDrawer.notification.priority"></span></div>
                                <div class="flex justify-between"><span class="text-slate-400">Time</span><span class="font-medium text-slate-800" x-text="$store.detailDrawer.notification.timestamp"></span></div>
                            </div>

                            <div class="mt-6 space-y-2">
                                <a :href="$store.detailDrawer.notification.deep_link" class="block rounded-full bg-blue-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-blue-700">Open Related Page</a>
                                <button @click="markRead($store.detailDrawer.notification.id); $store.detailDrawer.open = false" class="block w-full rounded-full border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:border-slate-300">Mark as Read</button>
                                <button @click="$store.detailDrawer.open = false" class="block w-full rounded-full border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        {{-- ============ Long-press action sheet (mobile) ============ --}}
        <template x-teleport="body">
            <div x-show="$store.actionSheet.open" x-cloak class="fixed inset-0 z-[110] sm:hidden">
                <div class="absolute inset-0 bg-slate-900/40" @click="$store.actionSheet.open = false"></div>
                <div x-show="$store.actionSheet.open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                     class="absolute inset-x-0 bottom-0 rounded-t-3xl bg-white p-5">
                    <div class="mx-auto mb-4 h-1.5 w-10 rounded-full bg-slate-200"></div>
                    <template x-if="$store.actionSheet.notification">
                        <div class="space-y-1">
                            <p class="px-2 pb-2 text-sm font-semibold text-slate-900" x-text="$store.actionSheet.notification.title"></p>
                            <button @click="$store.detailDrawer.notification = $store.actionSheet.notification; $store.detailDrawer.open = true; $store.actionSheet.open = false" class="block w-full rounded-xl px-3 py-3 text-left text-sm font-medium text-slate-700 hover:bg-slate-50">View Details</button>
                            <button @click="archive($store.actionSheet.notification.id); $store.actionSheet.open = false" class="block w-full rounded-xl px-3 py-3 text-left text-sm font-medium text-slate-700 hover:bg-slate-50">Archive</button>
                            <button @click="$store.actionSheet.open = false" class="block w-full rounded-xl px-3 py-3 text-left text-sm font-medium text-rose-600 hover:bg-rose-50">Delete</button>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        {{-- ============ Notification Settings drawer ============ --}}
        <template x-teleport="body">
            <div x-show="$store.settingsDrawer.open" x-cloak class="fixed inset-0 z-[100]" @keydown.escape.window="$store.settingsDrawer.open = false">
                <div class="absolute inset-0 bg-slate-900/40" @click="$store.settingsDrawer.open = false"></div>
                <div x-show="$store.settingsDrawer.open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     class="absolute inset-y-0 right-0 w-full max-w-md overflow-y-auto bg-white shadow-2xl" x-data="settingsForm()">
                    <div class="flex items-center justify-between border-b border-slate-100 p-5">
                        <h3 class="font-display text-lg font-semibold text-slate-900">Notification Settings</h3>
                        <button @click="$store.settingsDrawer.open = false" class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="space-y-8 p-5">
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">In-App Notifications</h4>
                            <div class="mt-3 space-y-3">
                                <template x-for="cat in inAppCategories" :key="cat">
                                    <label class="flex items-center justify-between text-sm text-slate-700">
                                        <span x-text="cat"></span>
                                        <span class="toggle" :class="inApp[cat] ? 'on' : ''" @click="inApp[cat] = !inApp[cat]"><span></span></span>
                                    </label>
                                </template>
                            </div>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Push Notifications</h4>
                            <label class="mt-3 flex items-center justify-between text-sm text-slate-700">
                                Enable Push Notifications
                                <span class="toggle" :class="push ? 'on' : ''" @click="push = !push"><span></span></span>
                            </label>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Email Notifications</h4>
                            <label class="flex items-center justify-between text-sm text-slate-700">
                                Receive Email Notifications
                                <span class="toggle" :class="email ? 'on' : ''" @click="email = !email"><span></span></span>
                            </label>
                            <div class="mt-3">
                                <label class="field-label">Notification frequency</label>
                                <select x-model="frequency" class="field-input"><option>Immediately</option><option>Hourly Digest</option><option>Daily Digest</option><option>Weekly Summary</option></select>
                            </div>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Quiet Hours</h4>
                            <p class="mt-1 text-xs text-slate-500">No notifications during this period.</p>
                            <div class="mt-3 grid grid-cols-2 gap-3">
                                <div><label class="field-label">Start time</label><input type="time" x-model="quietStart" class="field-input"></div>
                                <div><label class="field-label">End time</label><input type="time" x-model="quietEnd" class="field-input"></div>
                            </div>
                        </section>
                    </div>

                    <div class="sticky bottom-0 border-t border-slate-100 bg-white p-5">
                        <button @click="save()" class="w-full rounded-full bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700">
                            <span x-show="!saved">Save Preferences</span>
                            <span x-show="saved">Saved!</span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('detailDrawer', { open: false, notification: null });
            Alpine.store('actionSheet', { open: false, notification: null });
            Alpine.store('settingsDrawer', { open: false });

            Alpine.data('notificationsPage', () => ({
                loading: true,
                mobileFiltersOpen: false,
                filters: { search: '', duration: 'This Week', status: 'All', category: '', priority: 'All', sort: 'newest' },
                sortLabels: { newest: 'Newest', oldest: 'Oldest', unread_first: 'Unread First' },

                init() { setTimeout(() => { this.loading = false; }, 300); },

                isVisible(el) {
                    const name = el.dataset.name || '';
                    const status = el.dataset.status || '';
                    const category = el.dataset.category || '';
                    const priority = el.dataset.priority || '';
                    const f = this.filters;

                    if (f.search && !name.includes(f.search.toLowerCase())) return false;
                    if (f.status !== 'All' && status !== f.status.toLowerCase()) return false;
                    if (f.category && category !== f.category.toLowerCase()) return false;
                    if (f.priority !== 'All' && priority !== f.priority.toLowerCase()) return false;

                    return true;
                },

                noResults() {
                    const cards = this.$el.querySelectorAll('[data-name]');
                    return cards.length > 0 && Array.from(cards).every(el => !this.isVisible(el));
                },

                markRead(id) {
                    // Replace with a real call, e.g. Livewire.dispatch('markNotificationRead', { id })
                    const el = this.$el.querySelector(`[data-name]:has(button[onclick*="${id}"])`);
                },

                markAllRead() {
                    // Replace with a real call, e.g. Livewire.dispatch('markAllNotificationsRead')
                },

                archive(id) {
                    // Replace with a real call, e.g. Livewire.dispatch('archiveNotification', { id })
                },

                deleteNotification(id, el) {
                    // Replace with a real call, e.g. Livewire.dispatch('deleteNotification', { id })
                    if (el) el.remove();
                },
            }));

            Alpine.data('swipeCard', () => ({
                swipeX: 0,
                startX: 0,
                touching: false,
                pressTimer: null,

                onTouchStart(e) {
                    this.startX = e.touches[0].clientX;
                    this.touching = true;
                    this.pressTimer = setTimeout(() => {
                        this.swipeX = 0;
                        this.touching = false;
                    }, 600);
                },

                onTouchMove(e) {
                    clearTimeout(this.pressTimer);
                    this.swipeX = e.touches[0].clientX - this.startX;
                },

                onTouchEnd(e, id) {
                    clearTimeout(this.pressTimer);
                    this.touching = false;
                    if (this.swipeX > 80) {
                        // swiped right — mark as read
                        this.swipeX = 0;
                    } else if (this.swipeX < -80) {
                        // swiped left — archive
                        this.swipeX = 0;
                    } else {
                        this.swipeX = 0;
                    }
                },
            }));

            Alpine.data('settingsForm', () => ({
                saved: false,
                inAppCategories: ['Budget updates', 'Expense updates', 'Bills', 'Groups', 'Savings', 'Invitations', 'Reminders', 'System Alerts'],
                inApp: { 'Budget updates': true, 'Expense updates': true, 'Bills': true, 'Groups': true, 'Savings': true, 'Invitations': true, 'Reminders': true, 'System Alerts': true },
                push: true,
                email: true,
                frequency: 'Immediately',
                quietStart: '22:00',
                quietEnd: '07:00',

                save() {
                    // Replace with a real call, e.g. Livewire.dispatch('saveNotificationPreferences', { ...this })
                    this.saved = true;
                    setTimeout(() => this.saved = false, 1500);
                },
            }));
        });
    </script>
</x-app-layout>