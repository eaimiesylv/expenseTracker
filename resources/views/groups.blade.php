<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4" x-data="{}">
            <div>
                <h2 class="font-display text-xl font-semibold leading-tight text-slate-900">Groups</h2>
                <p class="mt-0.5 text-sm text-slate-500">Manage your shared groups and collaborate with members.</p>
            </div>
            <button type="button" @click="$store.groupModal.mode = 'create'; $store.groupModal.data = null; $store.groupModal.open = true"
                    class="hidden items-center gap-2 rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 transition hover:bg-blue-700 sm:inline-flex">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
                Create Group
            </button>
        </div>
    </x-slot>

    <style>
        [x-cloak] { display: none !important; }
        .b-card{ background:#fff; border:1px solid #E5E9F0; border-radius:20px; box-shadow: 0 1px 2px rgba(15,23,42,0.03), 0 10px 28px -14px rgba(15,23,42,0.10); }
        .badge{ display:inline-flex; align-items:center; border-radius:9999px; padding:.2rem .6rem; font-size:.7rem; font-weight:600; }
        .status-dot{ height:.5rem; width:.5rem; border-radius:9999px; display:inline-block; }
        .avatar{ display:inline-flex; align-items:center; justify-content:center; height:1.8rem; width:1.8rem; border-radius:9999px; font-size:.65rem; font-weight:700; border:2px solid #fff; }
        .field-label{ font-size:.75rem; font-weight:600; color:#475569; }
        .field-input{ margin-top:.375rem; width:100%; border-radius:.75rem; border:1px solid #E2E8F0; padding:.625rem .875rem; font-size:.875rem; }
        .field-input:focus{ outline:none; border-color:#60A5FA; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .segbtn{ border-radius:.75rem; border:1px solid #E2E8F0; padding:.5rem .75rem; font-size:.75rem; font-weight:600; color:#64748B; }
        .segbtn.active{ border-color:#2563EB; background:#EFF6FF; color:#1D4ED8; }
        .toggle{ position:relative; width:2.5rem; height:1.4rem; border-radius:9999px; background:#E2E8F0; transition:background .15s ease; cursor:pointer; }
        .toggle.on{ background:#2563EB; }
        .toggle span{ position:absolute; top:2px; left:2px; height:1.1rem; width:1.1rem; border-radius:9999px; background:#fff; transition:transform .15s ease; }
        .toggle.on span{ transform:translateX(1.1rem); }
        .skeleton{ background: linear-gradient(90deg,#EEF1F6 25%,#F6F8FA 37%,#EEF1F6 63%); background-size:400% 100%; animation: shimmer 1.4s ease infinite; border-radius:16px; }
        @keyframes shimmer{ 0%{ background-position:100% 50%;} 100%{ background-position:0 50%;} }
    </style>

    {{-- ============================================================
         Fallback sample data — replace with a real Livewire component
         (e.g. App\Livewire\Groups\Index) passing $groups, $summary,
         etc. as public properties.

         Note: per the spec, "View" opens a dedicated Group Details
         page. To keep this a single reusable component I built it as
         a full-height slide-over drawer instead (same pattern as the
         Bills detail drawer) — say the word if you'd rather have a
         real /groups/{id} route + page instead.
    ============================================================ --}}
    @php
        $groupTypes = ['Family', 'Friends', 'Roommates', 'Church', 'Cooperative', 'Business', 'Event', 'Other'];
        $roleDescriptions = [
            'Owner' => 'Full control over the group, its members, and all its data.',
            'Editor' => 'Can create and edit budgets, expenses, and bills.',
            'Contributor' => 'Can add expenses and contribute to budgets, but cannot change settings.',
            'Viewer' => 'Can only view group information.',
        ];
        $rolePermissions = [
            'Owner' => ['view_budgets' => true, 'add_expenses' => true, 'edit_bills' => true, 'delete_group' => true],
            'Editor' => ['view_budgets' => true, 'add_expenses' => true, 'edit_bills' => true, 'delete_group' => false],
            'Contributor' => ['view_budgets' => true, 'add_expenses' => true, 'edit_bills' => false, 'delete_group' => false],
            'Viewer' => ['view_budgets' => true, 'add_expenses' => false, 'edit_bills' => false, 'delete_group' => false],
        ];

        $groups = $groups ?? collect([
            (object)[
                'id' => 1, 'name' => 'Family', 'icon' => '👨‍👩‍👧', 'description' => 'Monthly Household Expenses',
                'type' => 'Family', 'privacy' => 'Private', 'status' => 'Active', 'created_at' => '2026-01-14',
                'invite_link' => 'https://app.example.com/invite/FAMILY123XYZ',
                'invite_settings' => ['expires' => '7 Days', 'max_uses' => 'Unlimited', 'require_approval' => true],
                'invite_stats' => ['sent' => 12, 'accepted' => 8, 'pending' => 4],
                'members' => [
                    ['name' => 'Father', 'email' => 'father@email.com', 'phone' => '08010000001', 'role' => 'Owner', 'status' => 'Active'],
                    ['name' => 'Mother', 'email' => 'mother@email.com', 'phone' => '08010000002', 'role' => 'Editor', 'status' => 'Active'],
                    ['name' => 'John Doe', 'email' => 'john@email.com', 'phone' => '08012345678', 'role' => 'Editor', 'status' => 'Active'],
                    ['name' => 'Mary', 'email' => 'mary@email.com', 'phone' => '08010000003', 'role' => 'Contributor', 'status' => 'Active'],
                    ['name' => 'Peter', 'email' => 'peter@email.com', 'phone' => '08010000004', 'role' => 'Viewer', 'status' => 'Active'],
                    ['name' => 'Grandma', 'email' => 'grandma@email.com', 'phone' => '08010000005', 'role' => 'Viewer', 'status' => 'Pending Invitation'],
                ],
                'pending_invitations' => [
                    ['name' => 'Mary', 'email' => 'mary@email.com', 'sent' => 'Yesterday', 'status' => 'Pending'],
                    ['name' => 'Grandma', 'email' => 'grandma@email.com', 'sent' => '3 days ago', 'status' => 'Pending'],
                ],
                'stats' => ['budgets' => 3, 'expenses' => 48, 'bills' => 7, 'savings' => 2],
                'activity' => [
                    ['label' => 'John joined the group', 'date' => 'Today'],
                    ['label' => 'Mary recorded an expense', 'date' => 'Yesterday'],
                    ['label' => 'New budget created', 'date' => '2 days ago'],
                    ['label' => 'Invitation accepted', 'date' => '3 days ago'],
                ],
                'insights' => [
                    'Family has recorded 48 expenses this month.',
                    '3 members haven\'t contributed to the current budget.',
                    'Electricity is the highest spending category.',
                    'Bills increased by 12% this month.',
                    '2 pending invitations haven\'t been accepted.',
                ],
            ],
            (object)[
                'id' => 2, 'name' => 'Church Committee', 'icon' => '⛪', 'description' => 'Welfare and building fund coordination',
                'type' => 'Church', 'privacy' => 'Private', 'status' => 'Active', 'created_at' => '2026-02-02',
                'invite_link' => 'https://app.example.com/invite/CHURCH02XYZ',
                'invite_settings' => ['expires' => '30 Days', 'max_uses' => 'Unlimited', 'require_approval' => false],
                'invite_stats' => ['sent' => 40, 'accepted' => 38, 'pending' => 2],
                'members' => [
                    ['name' => 'Pastor Ade', 'email' => 'ade@church.com', 'phone' => '08020000001', 'role' => 'Owner', 'status' => 'Active'],
                    ['name' => 'Ifeoma', 'email' => 'ifeoma@church.com', 'phone' => '08020000002', 'role' => 'Editor', 'status' => 'Active'],
                    ['name' => 'Tunde', 'email' => 'tunde@church.com', 'phone' => '08020000003', 'role' => 'Contributor', 'status' => 'Active'],
                ],
                'pending_invitations' => [['name' => 'Segun', 'email' => 'segun@church.com', 'sent' => '2 days ago', 'status' => 'Pending']],
                'stats' => ['budgets' => 2, 'expenses' => 15, 'bills' => 4, 'savings' => 1],
                'activity' => [['label' => 'Ifeoma recorded an expense', 'date' => 'Yesterday'], ['label' => 'Group created', 'date' => '5 months ago']],
                'insights' => ['Welfare bills account for 60% of group spending.', '1 pending invitation hasn\'t been accepted.'],
            ],
            (object)[
                'id' => 3, 'name' => 'Office Savings', 'icon' => '💼', 'description' => 'Colleague savings circle',
                'type' => 'Business', 'privacy' => 'Public', 'status' => 'Archived', 'created_at' => '2025-11-20',
                'invite_link' => 'https://app.example.com/invite/OFFICE20XYZ',
                'invite_settings' => ['expires' => 'Never', 'max_uses' => '20', 'require_approval' => true],
                'invite_stats' => ['sent' => 8, 'accepted' => 8, 'pending' => 0],
                'members' => [
                    ['name' => 'Ada', 'email' => 'ada@office.com', 'phone' => '08030000001', 'role' => 'Owner', 'status' => 'Active'],
                    ['name' => 'Bayo', 'email' => 'bayo@office.com', 'phone' => '08030000002', 'role' => 'Viewer', 'status' => 'Active'],
                ],
                'pending_invitations' => [],
                'stats' => ['budgets' => 1, 'expenses' => 12, 'bills' => 3, 'savings' => 1],
                'activity' => [['label' => 'Group archived', 'date' => '2 months ago']],
                'insights' => ['This group has been inactive for 2 months.'],
            ],
        ]);

        $totalGroups = $groups->count();
        $totalMembers = $groups->sum(fn ($g) => count($g->members));
        $activeGroups = $groups->where('status', 'Active')->count();
        $pendingInvites = $groups->sum(fn ($g) => count($g->pending_invitations));
        $newMembersThisMonth = 5;
        $largestGroup = $groups->sortByDesc(fn ($g) => count($g->members))->first();

        $summaryCards = [
            ['label' => 'Total Groups', 'value' => $totalGroups, 'trend' => '+2 this month', 'up' => true, 'icon' => 'layers'],
            ['label' => 'Total Members', 'value' => $totalMembers, 'trend' => '+3 this month', 'up' => true, 'icon' => 'users'],
            ['label' => 'Active Groups', 'value' => $activeGroups, 'trend' => 'Steady', 'up' => null, 'icon' => 'check'],
            ['label' => 'Pending Invitations', 'value' => $pendingInvites, 'trend' => 'Awaiting response', 'up' => null, 'icon' => 'mail'],
            ['label' => 'New Members This Month', 'value' => $newMembersThisMonth, 'trend' => '+5 this month', 'up' => true, 'icon' => 'user-plus'],
            ['label' => 'Largest Group', 'value' => $largestGroup->name . ' (' . count($largestGroup->members) . ')', 'trend' => 'Most collaborative', 'up' => null, 'icon' => 'star'],
        ];

        $roleBadge = ['Owner' => 'bg-blue-50 text-blue-700', 'Editor' => 'bg-purple-50 text-purple-700', 'Contributor' => 'bg-emerald-50 text-emerald-700', 'Viewer' => 'bg-slate-100 text-slate-600'];
        $avatarColors = ['bg-blue-500', 'bg-emerald-500', 'bg-orange-500', 'bg-purple-500', 'bg-rose-500', 'bg-teal-500'];
    @endphp

    <div x-data="groupsPage()" x-init="init()" x-cloak class="relative mx-auto max-w-7xl space-y-8 pb-20">

        {{-- Floating Create Group button (mobile) --}}
        <button type="button" @click="$store.groupModal.mode = 'create'; $store.groupModal.data = null; $store.groupModal.open = true"
                class="fixed bottom-6 right-6 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-300 sm:hidden">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
        </button>

        @if ($groups->isEmpty())
            <div class="b-card flex flex-col items-center gap-4 px-6 py-20 text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.5"/><path d="M3.5 19c.7-3 3-5 5.5-5s4.8 2 5.5 5M14.5 19c.3-2 1.6-3.6 3.3-4.3"/></svg>
                </div>
                <div>
                    <p class="font-display text-lg font-semibold text-slate-900">No Groups Yet</p>
                    <p class="mt-1 text-sm text-slate-500">Create your first group to start collaborating.</p>
                </div>
                <button type="button" @click="$store.groupModal.mode = 'create'; $store.groupModal.data = null; $store.groupModal.open = true"
                        class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700">Create Group</button>
            </div>
        @else

            {{-- Summary cards --}}
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($summaryCards as $card)
                    <div class="b-card p-5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            @switch($card['icon'])
                                @case('layers')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 13 9 5 9-5"/></svg>
                                    @break
                                @case('users')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.5"/><path d="M3.5 19c.7-3 3-5 5.5-5s4.8 2 5.5 5M14.5 19c.3-2 1.6-3.6 3.3-4.3"/></svg>
                                    @break
                                @case('check')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m5 13 4 4L19 7"/></svg>
                                    @break
                                @case('mail')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                                    @break
                                @case('user-plus')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3.5"/><path d="M2 20c1-3.5 4-5.5 7-5.5s6 2 7 5.5"/><path d="M19 8v6M22 11h-6"/></svg>
                                    @break
                                @case('star')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z"/></svg>
                                    @break
                                @default
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/></svg>
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
                        <input type="text" x-model="filters.search" placeholder="Search group, description, member..."
                               class="w-full rounded-full border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-4 text-sm focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    <button type="button" @click="mobileFiltersOpen = true" class="flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-600 sm:hidden">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
                        Filters
                    </button>
                </div>

                <div class="mt-3 hidden flex-wrap items-center gap-2 sm:flex">
                    <template x-for="opt in ['All Groups','My Groups','Active','Archived']" :key="opt">
                        <button type="button" @click="filters.status = opt" :class="filters.status === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                    </template>

                    <div class="relative ml-auto" x-data="{ sortOpen: false }">
                        <button type="button" @click="sortOpen = !sortOpen" @click.away="sortOpen = false" class="flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M6 12h12M10 18h4"/></svg>
                            <span x-text="sortLabels[filters.sort]"></span>
                        </button>
                        <div x-show="sortOpen" x-transition class="absolute right-0 z-20 mt-2 w-44 rounded-xl border border-slate-100 bg-white p-1.5 shadow-lg">
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
                                <label class="field-label">Show</label>
                                <div class="mt-1.5 grid grid-cols-2 gap-2">
                                    <template x-for="opt in ['All Groups','My Groups','Active','Archived']" :key="opt">
                                        <button type="button" @click="filters.status = opt" :class="filters.status === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
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

            {{-- Group cards --}}
            <div x-show="loading" class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                <div class="skeleton h-64"></div><div class="skeleton h-64"></div><div class="skeleton h-64"></div>
            </div>

            <div x-show="!loading" class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($groups as $g)
                    <div class="b-card p-5" data-name="{{ strtolower($g->name . ' ' . $g->description) }}" data-status="{{ strtolower($g->status) }}"
                         x-show="isVisible($el)" x-data="{ confirmDelete: false }">

                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-xl">{{ $g->icon }}</span>
                                <div>
                                    <h3 class="font-display text-base font-semibold text-slate-900">{{ $g->name }}</h3>
                                    <p class="text-xs text-slate-500">{{ count($g->members) }} Members</p>
                                </div>
                            </div>
                            <span class="badge {{ $g->status === 'Active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $g->status }}</span>
                        </div>

                        <p class="mt-3 text-sm text-slate-600">{{ $g->description }}</p>
                        <p class="mt-1 text-xs text-slate-400">Created {{ \Carbon\Carbon::parse($g->created_at)->format('M Y') }}</p>

                        <div class="mt-3 flex -space-x-2">
                            @foreach (array_slice($g->members, 0, 4) as $i => $m)
                                <span class="avatar {{ $avatarColors[$i % count($avatarColors)] }} text-white">{{ strtoupper(substr($m['name'],0,1)) }}</span>
                            @endforeach
                            @if (count($g->members) > 4)
                                <span class="avatar bg-slate-200 text-slate-600">+{{ count($g->members) - 4 }}</span>
                            @endif
                        </div>

                        <div class="mt-4 grid grid-cols-4 gap-2 rounded-xl bg-slate-50 p-3 text-center">
                            <div><p class="font-mono text-sm font-semibold text-slate-900">{{ $g->stats['budgets'] }}</p><p class="text-[10px] text-slate-400">Budgets</p></div>
                            <div><p class="font-mono text-sm font-semibold text-slate-900">{{ $g->stats['expenses'] }}</p><p class="text-[10px] text-slate-400">Expenses</p></div>
                            <div><p class="font-mono text-sm font-semibold text-slate-900">{{ $g->stats['bills'] }}</p><p class="text-[10px] text-slate-400">Bills</p></div>
                            <div><p class="font-mono text-sm font-semibold text-slate-900">{{ $g->stats['savings'] }}</p><p class="text-[10px] text-slate-400">Savings</p></div>
                        </div>

                        <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3" x-show="!confirmDelete">
                            <button type="button" @click="$store.groupDrawer.group=@js($g); $store.groupDrawer.tab='overview'; $store.groupDrawer.open=true"
                                    class="rounded-full border border-slate-200 px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300">View</button>
                            <button type="button" @click="$store.groupModal.mode='edit'; $store.groupModal.data=@js($g); $store.groupModal.open=true"
                                    class="rounded-full border border-slate-200 px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300">Edit</button>
                            <button type="button" @click="confirmDelete = true" class="rounded-full border border-rose-200 px-3.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                        </div>
                        <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3 text-sm" x-show="confirmDelete" x-cloak>
                            <span class="text-slate-600">Delete this group?</span>
                            <button type="button" @click="confirmDelete = false" class="ml-auto rounded-full px-3.5 py-1.5 text-xs font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                            <button type="button" @click="confirmDelete = false /* dispatch delete here */" class="rounded-full bg-rose-600 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Yes, delete</button>
                        </div>
                    </div>
                @endforeach

                <p x-show="noResults()" class="b-card p-8 text-center text-sm text-slate-500 sm:col-span-2 xl:col-span-3">No groups match your search or filters.</p>
            </div>
        @endif

        {{-- ============ Group Detail Drawer ============ --}}
        <template x-teleport="body">
            <div x-show="$store.groupDrawer.open" x-cloak class="fixed inset-0 z-[95]" @keydown.escape.window="$store.groupDrawer.open = false">
                <div class="absolute inset-0 bg-slate-900/40" @click="$store.groupDrawer.open = false"></div>
                <div x-show="$store.groupDrawer.open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                     class="absolute inset-y-0 right-0 flex w-full max-w-xl flex-col overflow-y-auto bg-white shadow-2xl">

                    <template x-if="$store.groupDrawer.group">
                        <div class="flex flex-1 flex-col">
                            {{-- Hero --}}
                            <div class="border-b border-slate-100 bg-gradient-to-br from-blue-50 to-white p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-2xl shadow-sm" x-text="$store.groupDrawer.group.icon"></span>
                                        <div>
                                            <h3 class="font-display text-lg font-semibold text-slate-900" x-text="$store.groupDrawer.group.name"></h3>
                                            <p class="text-sm text-slate-500" x-text="$store.groupDrawer.group.description"></p>
                                            <p class="mt-0.5 text-xs text-slate-400" x-text="$store.groupDrawer.group.members.length + ' Members · Created ' + $store.groupDrawer.group.created_at"></p>
                                        </div>
                                    </div>
                                    <button @click="$store.groupDrawer.open = false" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-slate-400 hover:bg-white">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <button @click="$store.groupModal.mode='edit'; $store.groupModal.data=$store.groupDrawer.group; $store.groupModal.open=true"
                                            class="rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:border-slate-300">Edit Group</button>
                                    <button @click="$store.inviteModal.group = $store.groupDrawer.group; $store.inviteModal.open = true"
                                            class="rounded-full bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">Invite Members</button>
                                    <button @click="$store.groupDrawer.confirmDelete = true" class="rounded-full border border-rose-200 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                                </div>
                                <div x-show="$store.groupDrawer.confirmDelete" x-cloak x-transition class="mt-3 rounded-xl bg-rose-50 p-3.5 text-sm">
                                    <p class="font-medium text-rose-700">Delete Group?</p>
                                    <p class="mt-1 text-xs text-rose-600">Deleting this group will not delete existing budgets, expenses or bills. Those records remain in the system but will no longer be associated with this group unless reassigned.</p>
                                    <div class="mt-3 flex justify-end gap-2">
                                        <button @click="$store.groupDrawer.confirmDelete = false" class="rounded-full px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-white">Cancel</button>
                                        <button @click="$store.groupDrawer.confirmDelete = false; $store.groupDrawer.open = false /* dispatch delete here */" class="rounded-full bg-rose-600 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Delete Group</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Overview stat row --}}
                            <div class="grid grid-cols-4 divide-x divide-slate-100 border-b border-slate-100 text-center">
                                <div class="p-3"><p class="font-mono text-lg font-semibold text-slate-900" x-text="$store.groupDrawer.group.members.length"></p><p class="text-[10px] text-slate-400">Members</p></div>
                                <div class="p-3"><p class="font-mono text-lg font-semibold text-slate-900" x-text="$store.groupDrawer.group.stats.budgets"></p><p class="text-[10px] text-slate-400">Budgets</p></div>
                                <div class="p-3"><p class="font-mono text-lg font-semibold text-slate-900" x-text="$store.groupDrawer.group.stats.expenses"></p><p class="text-[10px] text-slate-400">Expenses</p></div>
                                <div class="p-3"><p class="font-mono text-lg font-semibold text-slate-900" x-text="$store.groupDrawer.group.stats.bills"></p><p class="text-[10px] text-slate-400">Bills</p></div>
                            </div>

                            {{-- Tabs --}}
                            <div class="flex gap-1 overflow-x-auto border-b border-slate-100 px-5 py-2">
                                <template x-for="t in ['overview','members','budgets','expenses','bills','activity','settings']" :key="t">
                                    <button @click="$store.groupDrawer.tab = t" :class="$store.groupDrawer.tab === t ? 'segbtn active' : 'segbtn'" class="shrink-0 capitalize" x-text="t"></button>
                                </template>
                            </div>

                            <div class="flex-1 space-y-5 p-5">
                                {{-- Overview --}}
                                <div x-show="$store.groupDrawer.tab === 'overview'" class="space-y-4">
                                    <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Group Insights</h4>
                                    <ul class="space-y-2">
                                        <template x-for="(insight, i) in $store.groupDrawer.group.insights" :key="i">
                                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                                <svg class="mt-0.5 shrink-0 text-blue-500" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 3 21h18L12 2Z"/></svg>
                                                <span x-text="insight"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>

                                {{-- Members --}}
                                <div x-show="$store.groupDrawer.tab === 'members'" class="space-y-3">
                                    <button @click="$store.memberModal.mode='create'; $store.memberModal.data=null; $store.memberModal.open=true"
                                            class="w-full rounded-xl border border-dashed border-blue-200 py-2.5 text-xs font-semibold text-blue-600 hover:bg-blue-50">+ Add Member</button>
                                    <template x-for="(m, i) in $store.groupDrawer.group.members" :key="i">
                                        <div class="rounded-xl border border-slate-100 p-3.5">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2.5">
                                                    <span class="avatar bg-blue-500 text-white" x-text="m.name.charAt(0).toUpperCase()"></span>
                                                    <div>
                                                        <p class="text-sm font-medium text-slate-800" x-text="m.name"></p>
                                                        <p class="text-xs text-slate-400" x-text="m.email"></p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="badge" :class="{'bg-blue-50 text-blue-700': m.role==='Owner','bg-purple-50 text-purple-700': m.role==='Editor','bg-emerald-50 text-emerald-700': m.role==='Contributor','bg-slate-100 text-slate-600': m.role==='Viewer'}" x-text="m.role"></span>
                                                </div>
                                            </div>
                                            <div class="mt-2 flex items-center justify-between">
                                                <span class="text-xs" :class="m.status === 'Active' ? 'text-emerald-600' : 'text-amber-600'" x-text="m.status"></span>
                                                <div class="flex gap-2">
                                                    <button @click="$store.memberDrawer.member = m; $store.memberDrawer.open = true" class="text-xs font-semibold text-slate-500 hover:text-slate-800">View</button>
                                                    <button @click="$store.memberModal.mode='edit'; $store.memberModal.data=m; $store.memberModal.open=true" class="text-xs font-semibold text-slate-500 hover:text-slate-800">Edit</button>
                                                    <button @click="$store.groupDrawer.group.members.splice(i,1)" class="text-xs font-semibold text-rose-500 hover:text-rose-700">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <div class="mt-4">
                                        <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Pending Invitations</h4>
                                        <template x-if="!$store.groupDrawer.group.pending_invitations.length">
                                            <p class="mt-2 text-sm text-slate-400">No pending invitations.</p>
                                        </template>
                                        <template x-for="(inv, i) in $store.groupDrawer.group.pending_invitations" :key="i">
                                            <div class="mt-2 flex items-center justify-between rounded-xl bg-amber-50 p-3 text-sm">
                                                <div><p class="font-medium text-slate-800" x-text="inv.name"></p><p class="text-xs text-slate-500" x-text="inv.email + ' · Sent ' + inv.sent"></p></div>
                                                <div class="flex gap-2">
                                                    <button class="text-xs font-semibold text-blue-600 hover:underline">Resend</button>
                                                    <button @click="$store.groupDrawer.group.pending_invitations.splice(i,1)" class="text-xs font-semibold text-rose-600 hover:underline">Cancel</button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Budgets / Expenses / Bills quick counts (link out to their modules) --}}
                                <div x-show="$store.groupDrawer.tab === 'budgets'" class="rounded-xl bg-slate-50 p-4 text-sm text-slate-600">
                                    <span x-text="$store.groupDrawer.group.stats.budgets"></span> budgets belong to this group. Manage them from the Budgets page.
                                </div>
                                <div x-show="$store.groupDrawer.tab === 'expenses'" class="rounded-xl bg-slate-50 p-4 text-sm text-slate-600">
                                    <span x-text="$store.groupDrawer.group.stats.expenses"></span> expenses recorded for this group. Manage them from the Expenses page.
                                </div>
                                <div x-show="$store.groupDrawer.tab === 'bills'" class="rounded-xl bg-slate-50 p-4 text-sm text-slate-600">
                                    <span x-text="$store.groupDrawer.group.stats.bills"></span> bills linked to this group. Manage them from the Bills &amp; Splits page.
                                </div>

                                {{-- Activity --}}
                                <div x-show="$store.groupDrawer.tab === 'activity'" class="space-y-2">
                                    <template x-for="(a, i) in $store.groupDrawer.group.activity" :key="i">
                                        <div class="flex items-center justify-between border-l-2 border-slate-100 pl-3 text-sm">
                                            <span class="text-slate-700" x-text="a.label"></span>
                                            <span class="text-xs text-slate-400" x-text="a.date"></span>
                                        </div>
                                    </template>
                                </div>

                                {{-- Settings --}}
                                <div x-show="$store.groupDrawer.tab === 'settings'" class="space-y-2.5">
                                    <button @click="$store.groupModal.mode='edit'; $store.groupModal.data=$store.groupDrawer.group; $store.groupModal.open=true"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-left text-sm font-medium text-slate-700 hover:border-slate-300">Edit Group</button>
                                    <button @click="$store.inviteModal.group = $store.groupDrawer.group; $store.inviteModal.open = true"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-left text-sm font-medium text-slate-700 hover:border-slate-300">Invite Members</button>
                                    <button class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-left text-sm font-medium text-slate-700 hover:border-slate-300">Archive Group</button>
                                    <button @click="$store.groupDrawer.confirmDelete = true" class="w-full rounded-xl border border-rose-200 px-4 py-2.5 text-left text-sm font-medium text-rose-600 hover:bg-rose-50">Delete Group</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        {{-- ============ Member Detail Drawer ============ --}}
        <template x-teleport="body">
            <div x-show="$store.memberDrawer.open" x-cloak class="fixed inset-0 z-[105]" @keydown.escape.window="$store.memberDrawer.open = false">
                <div class="absolute inset-0 bg-slate-900/40" @click="$store.memberDrawer.open = false"></div>
                <div x-show="$store.memberDrawer.open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     class="absolute inset-y-0 right-0 flex w-full max-w-sm flex-col overflow-y-auto bg-white shadow-2xl">
                    <template x-if="$store.memberDrawer.member">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-500 text-lg font-semibold text-white" x-text="$store.memberDrawer.member.name.charAt(0).toUpperCase()"></span>
                                    <div>
                                        <p class="font-display text-base font-semibold text-slate-900" x-text="$store.memberDrawer.member.name"></p>
                                        <p class="text-xs text-slate-500" x-text="$store.memberDrawer.member.email"></p>
                                    </div>
                                </div>
                                <button @click="$store.memberDrawer.open = false" class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <div class="mt-5 space-y-3 rounded-xl bg-slate-50 p-4 text-sm">
                                <div class="flex justify-between"><span class="text-slate-400">Phone</span><span class="font-medium text-slate-800" x-text="$store.memberDrawer.member.phone"></span></div>
                                <div class="flex justify-between"><span class="text-slate-400">Role</span><span class="font-medium text-slate-800" x-text="$store.memberDrawer.member.role"></span></div>
                                <div class="flex justify-between"><span class="text-slate-400">Status</span><span class="font-medium text-slate-800" x-text="$store.memberDrawer.member.status"></span></div>
                            </div>

                            <div class="mt-5">
                                <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Activity</h4>
                                <ul class="mt-2 space-y-1.5 text-sm text-slate-600">
                                    <li>Budgets created: 2</li>
                                    <li>Expenses recorded: 14</li>
                                    <li>Bills created: 1</li>
                                    <li>Contributions made: 6</li>
                                </ul>
                            </div>

                            <div class="mt-6 flex gap-2">
                                <button @click="$store.memberModal.mode='edit'; $store.memberModal.data=$store.memberDrawer.member; $store.memberModal.open=true"
                                        class="flex-1 rounded-full border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:border-slate-300">Edit Member</button>
                                <button class="flex-1 rounded-full border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50">Remove Member</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        {{-- ============ Invite Members panel ============ --}}
        <template x-teleport="body">
            <div x-show="$store.inviteModal.open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="$store.inviteModal.open = false">
                <div x-show="$store.inviteModal.open" x-transition.opacity class="absolute inset-0 bg-slate-900/40" @click="$store.inviteModal.open = false"></div>
                <div x-show="$store.inviteModal.open" x-transition x-data="{ copied: false }" class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
                    <h3 class="font-display text-lg font-semibold text-slate-900">Invite Members</h3>
                    <p class="mt-0.5 text-sm text-slate-500">Share the link below to allow people to join this group.</p>

                    <div class="mt-4 flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <span class="flex-1 truncate font-mono text-xs text-slate-600" x-text="$store.inviteModal.group?.invite_link"></span>
                        <button type="button" @click="navigator.clipboard.writeText($store.inviteModal.group.invite_link); copied = true; setTimeout(() => copied = false, 1500)"
                                class="shrink-0 rounded-full bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                            <span x-show="!copied">Copy Link</span><span x-show="copied">Copied!</span>
                        </button>
                    </div>

                    <div class="mt-3 grid grid-cols-3 gap-2">
                        <button type="button" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:border-slate-300">Generate New Link</button>
                        <a :href="'https://wa.me/?text=' + encodeURIComponent($store.inviteModal.group?.invite_link || '')" target="_blank" class="rounded-xl border border-slate-200 px-3 py-2 text-center text-xs font-semibold text-slate-700 hover:border-slate-300">Share via WhatsApp</a>
                        <a :href="'mailto:?body=' + encodeURIComponent($store.inviteModal.group?.invite_link || '')" class="rounded-xl border border-slate-200 px-3 py-2 text-center text-xs font-semibold text-slate-700 hover:border-slate-300">Share via Email</a>
                    </div>

                    <template x-if="$store.inviteModal.group">
                        <div class="mt-4 grid grid-cols-3 gap-3 rounded-xl bg-slate-50 p-4 text-sm">
                            <div><p class="text-xs text-slate-400">Expires</p><p class="font-medium text-slate-800" x-text="$store.inviteModal.group.invite_settings.expires"></p></div>
                            <div><p class="text-xs text-slate-400">Max Uses</p><p class="font-medium text-slate-800" x-text="$store.inviteModal.group.invite_settings.max_uses"></p></div>
                            <div><p class="text-xs text-slate-400">Approval</p><p class="font-medium text-slate-800" x-text="$store.inviteModal.group.invite_settings.require_approval ? 'Required' : 'Not required'"></p></div>
                        </div>
                    </template>

                    <template x-if="$store.inviteModal.group">
                        <div class="mt-4 grid grid-cols-3 gap-3 text-center text-sm">
                            <div class="rounded-xl bg-blue-50 p-3"><p class="font-mono text-lg font-semibold text-blue-700" x-text="$store.inviteModal.group.invite_stats.sent"></p><p class="text-xs text-blue-600">Sent</p></div>
                            <div class="rounded-xl bg-emerald-50 p-3"><p class="font-mono text-lg font-semibold text-emerald-700" x-text="$store.inviteModal.group.invite_stats.accepted"></p><p class="text-xs text-emerald-600">Accepted</p></div>
                            <div class="rounded-xl bg-amber-50 p-3"><p class="font-mono text-lg font-semibold text-amber-700" x-text="$store.inviteModal.group.invite_stats.pending"></p><p class="text-xs text-amber-600">Pending</p></div>
                        </div>
                    </template>

                    <button type="button" @click="$store.inviteModal.open = false" class="mt-6 w-full rounded-full px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-100">Close</button>
                </div>
            </div>
        </template>

        {{-- ============ Add / Edit Member modal ============ --}}
        <template x-teleport="body">
            <div x-show="$store.memberModal.open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="$store.memberModal.open = false">
                <div x-show="$store.memberModal.open" x-transition.opacity class="absolute inset-0 bg-slate-900/40" @click="$store.memberModal.open = false"></div>
                <div x-show="$store.memberModal.open" x-transition x-data="memberForm()" x-init="init()" class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl">
                    <h3 class="font-display text-lg font-semibold text-slate-900" x-text="$store.memberModal.mode === 'edit' ? 'Edit Member' : 'Add Member'"></h3>

                    <form class="mt-5 space-y-6" @submit.prevent="submit()">
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Basic Information</h4>
                            <div class="mt-3 space-y-3">
                                <div><label class="field-label">Full name *</label><input type="text" x-model="form.name" required class="field-input"></div>
                                <div><label class="field-label">Email *</label><input type="email" x-model="form.email" required class="field-input"></div>
                                <div><label class="field-label">Phone number *</label><input type="text" x-model="form.phone" required class="field-input"></div>
                                <div><label class="field-label">Profile image (optional)</label><input type="file" class="field-input"></div>
                            </div>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Role</h4>
                            <select x-model="form.role" class="field-input mt-3">
                                <template x-for="r in ['Owner','Editor','Contributor','Viewer']" :key="r"><option :value="r" x-text="r"></option></template>
                            </select>
                            <p class="mt-2 text-xs text-slate-500" x-text="roleDescriptions[form.role]"></p>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Permissions Preview</h4>
                            <div class="mt-3 space-y-2 rounded-xl bg-slate-50 p-4 text-sm">
                                <div class="flex items-center justify-between"><span class="text-slate-600">Can View Budgets</span><span :class="rolePermissions[form.role].view_budgets ? 'text-emerald-600' : 'text-rose-500'" x-text="rolePermissions[form.role].view_budgets ? '✓' : '✗'"></span></div>
                                <div class="flex items-center justify-between"><span class="text-slate-600">Can Add Expenses</span><span :class="rolePermissions[form.role].add_expenses ? 'text-emerald-600' : 'text-rose-500'" x-text="rolePermissions[form.role].add_expenses ? '✓' : '✗'"></span></div>
                                <div class="flex items-center justify-between"><span class="text-slate-600">Can Edit Bills</span><span :class="rolePermissions[form.role].edit_bills ? 'text-emerald-600' : 'text-rose-500'" x-text="rolePermissions[form.role].edit_bills ? '✓' : '✗'"></span></div>
                                <div class="flex items-center justify-between"><span class="text-slate-600">Can Delete Group</span><span :class="rolePermissions[form.role].delete_group ? 'text-emerald-600' : 'text-rose-500'" x-text="rolePermissions[form.role].delete_group ? '✓' : '✗'"></span></div>
                            </div>
                        </section>

                        <p x-show="error" x-text="error" class="text-sm font-medium text-rose-600"></p>

                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                            <button type="button" @click="$store.memberModal.open = false" class="rounded-full px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                            <template x-if="$store.memberModal.mode === 'edit'">
                                <button type="button" class="rounded-full border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50">Remove Member</button>
                            </template>
                            <button type="submit" :disabled="submitting" class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700 disabled:opacity-60">
                                <span x-show="!submitting" x-text="$store.memberModal.mode === 'edit' ? 'Update Member' : 'Add Member'"></span>
                                <span x-show="submitting">Saving…</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- ============ Create / Edit Group modal ============ --}}
        <template x-teleport="body">
            <div x-show="$store.groupModal.open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="$store.groupModal.open = false">
                <div x-show="$store.groupModal.open" x-transition.opacity class="absolute inset-0 bg-slate-900/40" @click="$store.groupModal.open = false"></div>
                <div x-show="$store.groupModal.open" x-transition x-data="groupForm()" x-init="init()" class="relative max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-8">

                    <div class="flex items-start justify-between">
                        <h3 class="font-display text-lg font-semibold text-slate-900" x-text="$store.groupModal.mode === 'edit' ? 'Edit Group' : 'Create Group'"></h3>
                        <button type="button" @click="$store.groupModal.open = false" class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form class="mt-6 space-y-8" @submit.prevent="submit()">
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Basic Information</h4>
                            <div class="mt-3 space-y-3">
                                <div><label class="field-label">Group name *</label><input type="text" x-model="form.name" required class="field-input" placeholder="e.g. Family"></div>
                                <div><label class="field-label">Description *</label><textarea x-model="form.description" required rows="2" class="field-input" placeholder="e.g. Monthly Household Expenses"></textarea></div>
                                <div><label class="field-label">Group image (optional)</label><input type="file" class="field-input"></div>
                            </div>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Group Type</h4>
                            <select x-model="form.type" class="field-input mt-3">
                                @foreach ($groupTypes as $t) <option>{{ $t }}</option> @endforeach
                            </select>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Privacy</h4>
                            <div class="mt-3 space-y-2">
                                <label class="flex items-start gap-3 rounded-xl border p-3.5" :class="form.privacy === 'Private' ? 'border-blue-400 bg-blue-50' : 'border-slate-200'">
                                    <input type="radio" value="Private" x-model="form.privacy" class="mt-1">
                                    <span><span class="block text-sm font-medium text-slate-800">Private</span><span class="text-xs text-slate-500">Only invited members can join.</span></span>
                                </label>
                                <label class="flex items-start gap-3 rounded-xl border p-3.5" :class="form.privacy === 'Public' ? 'border-blue-400 bg-blue-50' : 'border-slate-200'">
                                    <input type="radio" value="Public" x-model="form.privacy" class="mt-1">
                                    <span><span class="block text-sm font-medium text-slate-800">Public</span><span class="text-xs text-slate-500">Anyone with the invitation link can request access.</span></span>
                                </label>
                            </div>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Invitation Settings</h4>
                            <div class="mt-3 space-y-3">
                                <label class="flex items-center justify-between text-sm text-slate-700">Generate invitation link automatically
                                    <span class="toggle" :class="form.auto_link ? 'on' : ''" @click="form.auto_link = !form.auto_link"><span></span></span>
                                </label>
                                <div>
                                    <label class="field-label">Invitation expiry</label>
                                    <select x-model="form.expiry" class="field-input"><option>7 Days</option><option>30 Days</option><option>Never</option></select>
                                </div>
                                <label class="flex items-center justify-between text-sm text-slate-700">Require owner approval before joining
                                    <span class="toggle" :class="form.require_approval ? 'on' : ''" @click="form.require_approval = !form.require_approval"><span></span></span>
                                </label>
                                <label class="flex items-center justify-between text-sm text-slate-700">Allow multiple uses
                                    <span class="toggle" :class="form.multi_use ? 'on' : ''" @click="form.multi_use = !form.multi_use"><span></span></span>
                                </label>
                            </div>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Preview</h4>
                            <div class="mt-3 rounded-xl bg-slate-50 p-4 text-sm">
                                <p class="font-semibold text-slate-900" x-text="form.name || '—'"></p>
                                <p class="text-slate-500" x-text="form.description || '—'"></p>
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    <span class="badge bg-blue-50 text-blue-700" x-text="form.privacy"></span>
                                    <span class="badge bg-emerald-50 text-emerald-700" x-show="form.auto_link">Invite Link Enabled</span>
                                </div>
                            </div>
                        </section>

                        <p x-show="error" x-text="error" class="text-sm font-medium text-rose-600"></p>

                        <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-5">
                            <button type="button" @click="$store.groupModal.open = false" class="rounded-full px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                            <template x-if="$store.groupModal.mode === 'edit'">
                                <button type="button" @click="deleting = true" class="rounded-full border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50">Delete Group</button>
                            </template>
                            <button type="submit" :disabled="submitting" class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700 disabled:opacity-60">
                                <span x-show="!submitting" x-text="$store.groupModal.mode === 'edit' ? 'Update Group' : 'Create Group'"></span>
                                <span x-show="submitting">Saving…</span>
                            </button>
                        </div>

                        <div x-show="deleting" x-cloak x-transition class="rounded-xl bg-rose-50 p-3.5 text-sm">
                            <p class="font-medium text-rose-700">Delete Group?</p>
                            <p class="mt-1 text-xs text-rose-600">Deleting this group will not delete existing budgets, expenses or bills. Those records remain in the system but will no longer be associated with this group unless reassigned.</p>
                            <div class="mt-3 flex justify-end gap-2">
                                <button type="button" @click="deleting = false" class="rounded-full px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-white">Cancel</button>
                                <button type="button" @click="deleting = false; $store.groupModal.open = false /* dispatch delete here */" class="rounded-full bg-rose-600 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Delete Group</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('groupModal', { open: false, mode: 'create', data: null });
            Alpine.store('groupDrawer', { open: false, group: null, tab: 'overview', confirmDelete: false });
            Alpine.store('memberDrawer', { open: false, member: null });
            Alpine.store('memberModal', { open: false, mode: 'create', data: null });
            Alpine.store('inviteModal', { open: false, group: null });

            Alpine.data('groupsPage', () => ({
                loading: true,
                mobileFiltersOpen: false,
                filters: { search: '', status: 'All Groups', sort: 'newest' },
                sortLabels: { newest: 'Newest', oldest: 'Oldest', most_members: 'Most Members', least_members: 'Least Members', recently_updated: 'Recently Updated' },

                init() { setTimeout(() => { this.loading = false; }, 300); },

                isVisible(el) {
                    const name = el.dataset.name || '';
                    const status = el.dataset.status || '';
                    const f = this.filters;

                    if (f.search && !name.includes(f.search.toLowerCase())) return false;
                    if (f.status === 'Active' && status !== 'active') return false;
                    if (f.status === 'Archived' && status !== 'archived') return false;

                    return true;
                },

                noResults() {
                    const cards = this.$el.querySelectorAll('[data-name]');
                    return cards.length > 0 && Array.from(cards).every(el => !this.isVisible(el));
                },
            }));

            Alpine.data('memberForm', () => ({
                submitting: false,
                error: '',
                roleDescriptions: @json($roleDescriptions),
                rolePermissions: @json($rolePermissions),
                form: {},

                init() {
                    this.form = this.blank();
                    this.$watch('$store.memberModal.data', (val) => {
                        this.form = val ? { name: val.name, email: val.email, phone: val.phone, role: val.role } : this.blank();
                        this.error = '';
                    });
                },

                blank() { return { name: '', email: '', phone: '', role: 'Viewer' }; },

                submit() {
                    if (!this.form.name || !this.form.email || !this.form.phone) {
                        this.error = 'Please fill in all required fields.';
                        return;
                    }
                    this.error = '';
                    this.submitting = true;
                    // Replace with a real call, e.g.:
                    // Livewire.dispatch($store.memberModal.mode === 'edit' ? 'updateMember' : 'addMember', { ...this.form })
                    setTimeout(() => { this.submitting = false; this.$store.memberModal.open = false; }, 500);
                },
            }));

            Alpine.data('groupForm', () => ({
                submitting: false,
                deleting: false,
                error: '',
                form: {},

                init() {
                    this.form = this.blank();
                    this.$watch('$store.groupModal.data', (val) => {
                        this.form = val ? this.hydrate(val) : this.blank();
                        this.deleting = false;
                        this.error = '';
                    });
                },

                blank() {
                    return { name: '', description: '', type: @json($groupTypes[0] ?? 'Family'), privacy: 'Private', auto_link: true, expiry: '7 Days', require_approval: true, multi_use: true };
                },

                hydrate(g) {
                    return {
                        name: g.name, description: g.description, type: g.type, privacy: g.privacy,
                        auto_link: true, expiry: g.invite_settings?.expires || '7 Days',
                        require_approval: g.invite_settings?.require_approval ?? true, multi_use: true,
                    };
                },

                submit() {
                    if (!this.form.name || !this.form.description) {
                        this.error = 'Please fill in all required fields.';
                        return;
                    }
                    this.error = '';
                    this.submitting = true;
                    // Replace with a real call, e.g.:
                    // Livewire.dispatch($store.groupModal.mode === 'edit' ? 'updateGroup' : 'createGroup', { ...this.form })
                    setTimeout(() => { this.submitting = false; this.$store.groupModal.open = false; }, 600);
                },
            }));
        });
    </script>
</x-app-layout>