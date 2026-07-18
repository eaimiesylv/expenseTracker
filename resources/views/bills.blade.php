<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4" x-data="{}">
            <div>
                <h2 class="font-display text-xl font-semibold leading-tight text-slate-900">Bills &amp; Splits</h2>
                <p class="mt-0.5 text-sm text-slate-500">Create, manage and track shared bills and payment collections.</p>
            </div>
            <button type="button" @click="$store.billModal.mode = 'create'; $store.billModal.data = null; $store.billModal.open = true"
                    class="hidden items-center gap-2 rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 transition hover:bg-blue-700 sm:inline-flex">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
                Create Bill
            </button>
        </div>
    </x-slot>

    <style>
        [x-cloak] { display: none !important; }
        .b-card{ background:#fff; border:1px solid #E5E9F0; border-radius:20px; box-shadow: 0 1px 2px rgba(15,23,42,0.03), 0 10px 28px -14px rgba(15,23,42,0.10); }
        .b-progress-track{ height:8px; border-radius:9999px; background:#EEF1F6; }
        .b-progress-fill{ height:8px; border-radius:9999px; transition: width .4s ease; }
        .badge{ display:inline-flex; align-items:center; border-radius:9999px; padding:.2rem .6rem; font-size:.7rem; font-weight:600; }
        .status-dot{ height:.5rem; width:.5rem; border-radius:9999px; display:inline-block; }
        .avatar{ display:inline-flex; align-items:center; justify-content:center; height:1.6rem; width:1.6rem; border-radius:9999px; font-size:.65rem; font-weight:700; border:2px solid #fff; }
        .field-label{ font-size:.75rem; font-weight:600; color:#475569; }
        .field-input{ margin-top:.375rem; width:100%; border-radius:.75rem; border:1px solid #E2E8F0; padding:.625rem .875rem; font-size:.875rem; }
        .field-input:focus{ outline:none; border-color:#60A5FA; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .segbtn{ border-radius:.75rem; border:1px solid #E2E8F0; padding:.5rem .75rem; font-size:.75rem; font-weight:600; color:#64748B; }
        .segbtn.active{ border-color:#2563EB; background:#EFF6FF; color:#1D4ED8; }
        .toggle{ position:relative; width:2.5rem; height:1.4rem; border-radius:9999px; background:#E2E8F0; transition:background .15s ease; }
        .toggle.on{ background:#2563EB; }
        .toggle span{ position:absolute; top:2px; left:2px; height:1.1rem; width:1.1rem; border-radius:9999px; background:#fff; transition:transform .15s ease; }
        .toggle.on span{ transform:translateX(1.1rem); }
        .skeleton{ background: linear-gradient(90deg,#EEF1F6 25%,#F6F8FA 37%,#EEF1F6 63%); background-size:400% 100%; animation: shimmer 1.4s ease infinite; border-radius:16px; }
        @keyframes shimmer{ 0%{ background-position:100% 50%;} 100%{ background-position:0 50%;} }
    </style>

    {{-- ============================================================
         Fallback sample data — replace with a real Livewire component
         (e.g. App\Livewire\Bills\Index) passing $bills, $summary,
         $attentionItems, $insights as public properties.
    ============================================================ --}}
    @php
        $groupsList = ['Family', 'Church cooperative', 'Office savings'];
        $categoriesList = ['Utilities', 'Rent', 'Food', 'Transport', 'Welfare', 'Others'];
        $memberDirectory = ['Father', 'Mother', 'Mary', 'John', 'Peter'];
        $budgetsList = $budgetsList ?? [
            (object)['name' => 'Food', 'type' => 'Personal'],
            (object)['name' => 'Family utilities', 'type' => 'Group', 'group' => 'Family'],
            (object)['name' => 'Transport', 'type' => 'Personal'],
        ];

        $bills = $bills ?? collect([
            (object)[
                'id' => 1, 'name' => 'Electricity Bill', 'description' => 'July NEPA bill for the house.',
                'amount' => 60000, 'type' => 'Group', 'group' => 'Family', 'category' => 'Utilities',
                'split_method' => 'Equal', 'due_date' => '2026-07-25', 'status' => 'Partial',
                'collected' => 45000, 'outstanding' => 15000, 'created_by' => 'Father',
                'public_link' => 'https://app.com/bill/ABC123XYZ',
                'allow_partial' => true, 'allow_proof' => true, 'auto_reminder' => true, 'reminder_frequency' => 'Weekly',
                'participants' => [
                    ['name' => 'Father', 'kind' => 'Registered', 'assigned' => 20000, 'paid' => 20000, 'status' => 'Paid'],
                    ['name' => 'Mother', 'kind' => 'Registered', 'assigned' => 20000, 'paid' => 10000, 'status' => 'Partial'],
                    ['name' => 'John', 'kind' => 'Registered', 'assigned' => 20000, 'paid' => 0, 'status' => 'Pending'],
                ],
                'payments' => [
                    ['name' => 'Father', 'amount' => 20000, 'date' => 'Jul 12'],
                    ['name' => 'Mother', 'amount' => 10000, 'date' => 'Jul 14'],
                ],
                'activity' => [
                    ['label' => 'Bill Created', 'date' => 'Jul 10'],
                    ['label' => 'Reminder Sent', 'date' => 'Jul 13'],
                    ['label' => 'Payment Recorded — Mother', 'date' => 'Jul 14'],
                ],
            ],
            (object)[
                'id' => 2, 'name' => 'Family Rent', 'description' => 'Quarterly rent contribution.',
                'amount' => 300000, 'type' => 'Group', 'group' => 'Family', 'category' => 'Rent',
                'split_method' => 'Equal', 'due_date' => '2026-07-20', 'status' => 'Overdue',
                'collected' => 150000, 'outstanding' => 150000, 'created_by' => 'Father',
                'public_link' => 'https://app.com/bill/RENT2026',
                'allow_partial' => true, 'allow_proof' => false, 'auto_reminder' => true, 'reminder_frequency' => 'Daily',
                'participants' => [
                    ['name' => 'Father', 'kind' => 'Registered', 'assigned' => 100000, 'paid' => 100000, 'status' => 'Paid'],
                    ['name' => 'Mother', 'kind' => 'Registered', 'assigned' => 100000, 'paid' => 50000, 'status' => 'Partial'],
                    ['name' => 'Peter', 'kind' => 'Guest', 'assigned' => 100000, 'paid' => 0, 'status' => 'Pending'],
                ],
                'payments' => [['name' => 'Father', 'amount' => 100000, 'date' => 'Jul 5'], ['name' => 'Mother', 'amount' => 50000, 'date' => 'Jul 8']],
                'activity' => [['label' => 'Bill Created', 'date' => 'Jul 1'], ['label' => 'Bill became overdue', 'date' => 'Jul 21']],
            ],
            (object)[
                'id' => 3, 'name' => 'Church Welfare', 'description' => 'Monthly welfare collection.',
                'amount' => 90000, 'type' => 'Group', 'group' => 'Church cooperative', 'category' => 'Welfare',
                'split_method' => 'Fixed', 'due_date' => '2026-07-30', 'status' => 'Active',
                'collected' => 45000, 'outstanding' => 45000, 'created_by' => 'Mary',
                'public_link' => 'https://app.com/bill/WELFARE30',
                'allow_partial' => true, 'allow_proof' => true, 'auto_reminder' => false, 'reminder_frequency' => 'Weekly',
                'participants' => [
                    ['name' => 'Mary', 'kind' => 'Registered', 'assigned' => 30000, 'paid' => 30000, 'status' => 'Paid'],
                    ['name' => 'John', 'kind' => 'Registered', 'assigned' => 30000, 'paid' => 15000, 'status' => 'Partial'],
                    ['name' => 'Peter', 'kind' => 'Registered', 'assigned' => 30000, 'paid' => 0, 'status' => 'Pending'],
                ],
                'payments' => [['name' => 'Mary', 'amount' => 30000, 'date' => 'Jul 2'], ['name' => 'John', 'amount' => 15000, 'date' => 'Jul 9']],
                'activity' => [['label' => 'Bill Created', 'date' => 'Jul 1'], ['label' => 'Bill Shared', 'date' => 'Jul 1']],
            ],
            (object)[
                'id' => 4, 'name' => 'Internet Bill', 'description' => 'Monthly fibre subscription.',
                'amount' => 25000, 'type' => 'Personal', 'group' => null, 'category' => 'Utilities',
                'split_method' => 'Equal', 'due_date' => '2026-07-10', 'status' => 'Overdue',
                'collected' => 0, 'outstanding' => 25000, 'created_by' => 'You',
                'public_link' => 'https://app.com/bill/NET2026',
                'allow_partial' => false, 'allow_proof' => false, 'auto_reminder' => true, 'reminder_frequency' => 'Weekly',
                'participants' => [['name' => 'You', 'kind' => 'Registered', 'assigned' => 25000, 'paid' => 0, 'status' => 'Pending']],
                'payments' => [], 'activity' => [['label' => 'Bill Created', 'date' => 'Jul 1']],
            ],
            (object)[
                'id' => 5, 'name' => 'Dinner Out', 'description' => 'Family dinner at Circle Mall.',
                'amount' => 80000, 'type' => 'Group', 'group' => 'Family', 'category' => 'Food',
                'split_method' => 'Equal', 'due_date' => '2026-07-13', 'status' => 'Paid',
                'collected' => 80000, 'outstanding' => 0, 'created_by' => 'Ada',
                'public_link' => 'https://app.com/bill/DINE13',
                'allow_partial' => false, 'allow_proof' => true, 'auto_reminder' => false, 'reminder_frequency' => 'Weekly',
                'participants' => [
                    ['name' => 'Ada', 'kind' => 'Registered', 'assigned' => 20000, 'paid' => 20000, 'status' => 'Paid'],
                    ['name' => 'Bayo', 'kind' => 'Registered', 'assigned' => 20000, 'paid' => 20000, 'status' => 'Paid'],
                    ['name' => 'Chika', 'kind' => 'Registered', 'assigned' => 20000, 'paid' => 20000, 'status' => 'Paid'],
                    ['name' => 'Dele', 'kind' => 'Guest', 'assigned' => 20000, 'paid' => 20000, 'status' => 'Paid'],
                ],
                'payments' => [
                    ['name' => 'Ada', 'amount' => 20000, 'date' => 'Jul 12'], ['name' => 'Bayo', 'amount' => 20000, 'date' => 'Jul 12'],
                    ['name' => 'Chika', 'amount' => 20000, 'date' => 'Jul 13'], ['name' => 'Dele', 'amount' => 20000, 'date' => 'Jul 13'],
                ],
                'activity' => [['label' => 'Bill Created', 'date' => 'Jul 12'], ['label' => 'Fully Collected', 'date' => 'Jul 13'], ['label' => 'Converted to Expense', 'date' => 'Jul 13']],
            ],
        ]);

        $totalBills = $bills->count();
        $activeBills = $bills->whereIn('status', ['Active', 'Partial'])->count();
        $paidBills = $bills->where('status', 'Paid')->count();
        $overdueBills = $bills->where('status', 'Overdue')->count();
        $outstandingAmount = $bills->sum('outstanding');
        $collectedAmount = $bills->sum('collected');
        $dueThisWeek = $bills->filter(fn ($b) => \Carbon\Carbon::parse($b->due_date)->between(now()->startOfWeek(), now()->endOfWeek()))->count();
        $collectionRate = $bills->sum('amount') > 0 ? round(($collectedAmount / $bills->sum('amount')) * 100) : 0;

        $summaryCards = [
            ['label' => 'Total Bills', 'value' => $totalBills, 'trend' => '+1 this week', 'up' => true, 'icon' => 'list'],
            ['label' => 'Active Bills', 'value' => $activeBills, 'trend' => 'In progress', 'up' => null, 'icon' => 'clock'],
            ['label' => 'Paid Bills', 'value' => $paidBills, 'trend' => '+1 vs last week', 'up' => true, 'icon' => 'check'],
            ['label' => 'Overdue Bills', 'value' => $overdueBills, 'trend' => 'Needs action', 'up' => false, 'icon' => 'alert'],
            ['label' => 'Outstanding Amount', 'value' => '₦' . number_format($outstandingAmount), 'trend' => '↓ 15% vs last week', 'up' => false, 'icon' => 'wallet'],
            ['label' => 'Amount Collected', 'value' => '₦' . number_format($collectedAmount), 'trend' => '↑ 22% vs last week', 'up' => true, 'icon' => 'trend-up'],
            ['label' => 'Bills Due This Week', 'value' => $dueThisWeek, 'trend' => 'Keep an eye on these', 'up' => null, 'icon' => 'calendar'],
            ['label' => 'Avg. Collection Rate', 'value' => $collectionRate . '%', 'trend' => '+5% vs last week', 'up' => true, 'icon' => 'gauge'],
        ];

        $attentionItems = $attentionItems ?? collect([
            (object)['label' => 'Electricity Bill', 'meta' => 'Due tomorrow', 'tone' => 'blue'],
            (object)['label' => 'Family Rent', 'meta' => '2 members have not paid', 'tone' => 'amber'],
            (object)['label' => 'Church Welfare', 'meta' => '₦45,000 outstanding', 'tone' => 'amber'],
            (object)['label' => 'Internet Bill', 'meta' => 'Past due', 'tone' => 'red'],
        ]);

        $insights = $insights ?? [
            '4 bills are overdue.',
            'Collection rate increased by 15%.',
            'Family bills account for 60% of all shared expenses.',
            'Average payment time is 4 days.',
            'Three bills are due this week.',
        ];

        $toneDot = ['blue' => 'bg-blue-500', 'amber' => 'bg-amber-500', 'red' => 'bg-rose-500', 'green' => 'bg-emerald-500'];
        $statusBadge = [
            'Draft' => 'bg-slate-100 text-slate-600', 'Active' => 'bg-blue-50 text-blue-700',
            'Paid' => 'bg-emerald-50 text-emerald-700', 'Partial' => 'bg-amber-50 text-amber-700',
            'Overdue' => 'bg-rose-50 text-rose-700',
        ];
        $participantStatusColor = ['Paid' => 'bg-emerald-500', 'Partial' => 'bg-amber-500', 'Pending' => 'bg-slate-400'];
        $avatarColors = ['bg-blue-500', 'bg-emerald-500', 'bg-orange-500', 'bg-purple-500', 'bg-rose-500'];
    @endphp

    <div x-data="billsPage()" x-init="init()" x-cloak class="relative mx-auto max-w-7xl space-y-8 pb-20">

        {{-- Floating Create Bill button (mobile) --}}
        <button type="button" @click="$store.billModal.mode = 'create'; $store.billModal.data = null; $store.billModal.open = true"
                class="fixed bottom-6 right-6 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-300 sm:hidden">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
        </button>

        @if ($bills->isEmpty())
            <div class="b-card flex flex-col items-center gap-4 px-6 py-20 text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h13l3 4v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Z"/><path d="M8 11h8M8 15h5"/></svg>
                </div>
                <div>
                    <p class="font-display text-lg font-semibold text-slate-900">No bills found.</p>
                    <p class="mt-1 text-sm text-slate-500">Create your first shared bill.</p>
                </div>
                <button type="button" @click="$store.billModal.mode = 'create'; $store.billModal.data = null; $store.billModal.open = true"
                        class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700">Create Bill</button>
            </div>
        @else

            {{-- Summary cards --}}
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($summaryCards as $card)
                    <div class="b-card p-5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            @switch($card['icon'])
                                @case('list')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                    @break
                                @case('clock')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                                    @break
                                @case('check')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m5 13 4 4L19 7"/></svg>
                                    @break
                                @case('wallet')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7a2 2 0 0 1 2-2h13a1 1 0 0 1 1 1v3"/><path d="M3 7v11a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-5a2 2 0 1 0 0 4"/></svg>
                                    @break
                                @case('trend-up')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 17 9 11l4 4 8-8M21 7h-6v6"/></svg>
                                    @break
                                @case('calendar')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
                                    @break
                                @case('gauge')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 14a8 8 0 1 1 16 0"/><path d="M12 14l4-4"/></svg>
                                    @break
                                @default
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10.3 3.9 1.8 18a1.5 1.5 0 0 0 1.3 2.2h17.8a1.5 1.5 0 0 0 1.3-2.2L13.7 3.9a1.5 1.5 0 0 0-2.6 0Z"/><path d="M12 9v4M12 17h.01"/></svg>
                            @endswitch
                        </span>
                        <p class="font-mono mt-4 text-2xl font-semibold text-slate-900">{{ $card['value'] }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-500">{{ $card['label'] }}</p>
                        @if (!is_null($card['up']))
                            <p class="mt-2 text-xs font-medium {{ $card['up'] ? 'text-emerald-600' : 'text-rose-600' }}">{{ $card['trend'] }} <span class="text-slate-400 font-normal">· vs last week</span></p>
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
                        <input type="text" x-model="filters.search" placeholder="Search bill, participant, group, creator..."
                               class="w-full rounded-full border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-4 text-sm focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div class="hidden items-center gap-2 sm:flex">
                        <input type="date" x-model="filters.start" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">
                        <span class="text-xs text-slate-400">to</span>
                        <input type="date" x-model="filters.end" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">
                    </div>

                    <button type="button" @click="mobileFiltersOpen = true" class="flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-600 sm:hidden">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
                        Filters
                    </button>
                </div>

                <div class="mt-3 hidden flex-wrap items-center gap-2 sm:flex">
                    <template x-for="d in datePresets" :key="d">
                        <button type="button" @click="applyPreset(d)" class="segbtn" x-text="d"></button>
                    </template>
                    <span class="mx-1 h-5 w-px bg-slate-200"></span>
                    <template x-for="opt in ['All','Draft','Active','Paid','Partial','Overdue']" :key="opt">
                        <button type="button" @click="filters.status = opt" :class="filters.status === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                    </template>
                </div>

                <div class="mt-2 hidden flex-wrap items-center gap-2 sm:flex">
                    <template x-for="opt in ['All','Personal','Group']" :key="opt">
                        <button type="button" @click="filters.type = opt" :class="filters.type === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                    </template>
                    <select x-show="filters.type === 'Group'" x-model="filters.group" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">
                        <option value="">All groups</option>
                        @foreach ($groupsList as $g) <option>{{ $g }}</option> @endforeach
                    </select>
                    <select x-model="filters.category" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">
                        <option value="">All categories</option>
                        @foreach ($categoriesList as $c) <option>{{ $c }}</option> @endforeach
                    </select>

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
                            <div class="grid grid-cols-2 gap-2">
                                <div><label class="field-label">Start date</label><input type="date" x-model="filters.start" class="field-input"></div>
                                <div><label class="field-label">End date</label><input type="date" x-model="filters.end" class="field-input"></div>
                            </div>
                            <div>
                                <label class="field-label">Quick presets</label>
                                <div class="mt-1.5 flex flex-wrap gap-2">
                                    <template x-for="d in datePresets" :key="d">
                                        <button type="button" @click="applyPreset(d)" class="segbtn" x-text="d"></button>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <label class="field-label">Status</label>
                                <div class="mt-1.5 grid grid-cols-3 gap-2">
                                    <template x-for="opt in ['All','Draft','Active','Paid','Partial','Overdue']" :key="opt">
                                        <button type="button" @click="filters.status = opt" :class="filters.status === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <label class="field-label">Bill type</label>
                                <div class="mt-1.5 grid grid-cols-3 gap-2">
                                    <template x-for="opt in ['All','Personal','Group']" :key="opt">
                                        <button type="button" @click="filters.type = opt" :class="filters.type === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                                    </template>
                                </div>
                            </div>
                            <div x-show="filters.type === 'Group'">
                                <label class="field-label">Group</label>
                                <select x-model="filters.group" class="field-input">
                                    <option value="">All groups</option>
                                    @foreach ($groupsList as $g) <option>{{ $g }}</option> @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="field-label">Category</label>
                                <select x-model="filters.category" class="field-input">
                                    <option value="">All categories</option>
                                    @foreach ($categoriesList as $c) <option>{{ $c }}</option> @endforeach
                                </select>
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

            {{-- Bill list + insights --}}
            <div class="grid gap-6 xl:grid-cols-[1fr_320px]">
                <div x-show="loading" class="space-y-4">
                    <div class="skeleton h-36"></div>
                    <div class="skeleton h-36"></div>
                    <div class="skeleton h-36"></div>
                </div>

                <div x-show="!loading" class="space-y-4">
                    @foreach ($bills as $bill)
                        @php
                            $pct = $bill->amount > 0 ? min(100, round(($bill->collected / $bill->amount) * 100)) : 0;
                            $tags = collect([$bill->type, $bill->status, $bill->group, $bill->category])->filter()->map(fn($t)=>strtolower($t))->implode('|');
                        @endphp
                        <div class="b-card p-5" data-name="{{ strtolower($bill->name) }}" data-tags="{{ $tags }}"
                             data-amount="{{ $bill->amount }}" data-due="{{ $bill->due_date }}"
                             x-show="isVisible($el)" x-data="{ confirmDelete: false }">

                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-display text-base font-semibold text-slate-900">{{ $bill->name }}</h3>
                                        <span class="font-mono text-sm font-semibold text-slate-700">₦{{ number_format($bill->amount) }}</span>
                                    </div>
                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        <span class="badge {{ $bill->type === 'Group' ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-600' }}">{{ $bill->type }}</span>
                                        <span class="badge {{ $statusBadge[$bill->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $bill->status }}</span>
                                        <span class="badge bg-slate-100 text-slate-600">{{ $bill->split_method }} Split</span>
                                    </div>
                                </div>
                                <div class="flex -space-x-2">
                                    @foreach (array_slice($bill->participants, 0, 3) as $i => $p)
                                        <span class="avatar {{ $avatarColors[$i % count($avatarColors)] }} text-white">{{ strtoupper(substr($p['name'],0,1)) }}</span>
                                    @endforeach
                                    @if (count($bill->participants) > 3)
                                        <span class="avatar bg-slate-200 text-slate-600">+{{ count($bill->participants) - 3 }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500">
                                @if ($bill->group)<span>{{ $bill->group }} Group</span>@endif
                                <span>Due {{ \Carbon\Carbon::parse($bill->due_date)->format('j M') }}</span>
                                <span>Created by {{ $bill->created_by }}</span>
                            </div>

                            <div class="mt-4 grid grid-cols-3 gap-3 text-sm">
                                <div><p class="text-xs text-slate-400">Collected</p><p class="font-mono font-semibold text-emerald-600">₦{{ number_format($bill->collected) }}</p></div>
                                <div><p class="text-xs text-slate-400">Outstanding</p><p class="font-mono font-semibold text-rose-600">₦{{ number_format($bill->outstanding) }}</p></div>
                                <div><p class="text-xs text-slate-400">Progress</p><p class="font-semibold text-slate-900">{{ $pct }}% Paid</p></div>
                            </div>
                            <div class="b-progress-track mt-2"><div class="b-progress-fill" style="width:{{ $pct }}%; background:{{ $pct >= 100 ? '#10B981' : ($pct > 0 ? '#F59E0B' : '#94A3B8') }};"></div></div>

                            <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3" x-show="!confirmDelete">
                                <button type="button" @click="$store.billDrawer.bill=@js($bill); $store.billDrawer.tab='overview'; $store.billDrawer.open=true"
                                        class="rounded-full border border-slate-200 px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300">View</button>
                                <button type="button" @click="$store.billModal.mode='edit'; $store.billModal.data=@js($bill); $store.billModal.open=true"
                                        class="rounded-full border border-slate-200 px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300">Edit</button>
                                <button type="button" @click="confirmDelete = true" class="rounded-full border border-rose-200 px-3.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                            </div>
                            <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3 text-sm" x-show="confirmDelete" x-cloak>
                                <span class="text-slate-600">Delete this bill?</span>
                                <button type="button" @click="confirmDelete = false" class="ml-auto rounded-full px-3.5 py-1.5 text-xs font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                                <button type="button" @click="confirmDelete = false /* dispatch delete here */" class="rounded-full bg-rose-600 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Yes, delete</button>
                            </div>
                        </div>
                    @endforeach

                    <p x-show="noResults()" class="b-card p-8 text-center text-sm text-slate-500">No bills match your filters.</p>
                </div>

                <div class="space-y-6">
                    <div class="b-card p-5">
                        <h3 class="font-display text-sm font-semibold text-slate-900">Smart Insights</h3>
                        <ul class="mt-3 space-y-2.5">
                            @foreach ($insights as $insight)
                                <li class="flex items-start gap-2 text-sm text-slate-600">
                                    <svg class="mt-0.5 shrink-0 text-blue-500" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 3 21h18L12 2Z"/></svg>
                                    {{ $insight }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- ============ Bill Detail Drawer ============ --}}
        <template x-teleport="body">
            <div x-show="$store.billDrawer.open" x-cloak class="fixed inset-0 z-[95]" @keydown.escape.window="$store.billDrawer.open = false">
                <div class="absolute inset-0 bg-slate-900/40" @click="$store.billDrawer.open = false"></div>
                <div x-show="$store.billDrawer.open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                     class="absolute inset-y-0 right-0 flex w-full max-w-md flex-col overflow-y-auto bg-white shadow-2xl">

                    <template x-if="$store.billDrawer.bill">
                        <div class="flex flex-1 flex-col">
                            <div class="flex items-start justify-between border-b border-slate-100 p-5">
                                <div>
                                    <h3 class="font-display text-lg font-semibold text-slate-900" x-text="$store.billDrawer.bill.name"></h3>
                                    <p class="mt-0.5 text-sm text-slate-500" x-text="'₦' + Number($store.billDrawer.bill.amount).toLocaleString()"></p>
                                </div>
                                <button @click="$store.billDrawer.open = false" class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <div class="flex gap-1 overflow-x-auto border-b border-slate-100 px-5 py-2">
                                <template x-for="t in ['overview','participants','payments','activity','settings']" :key="t">
                                    <button @click="$store.billDrawer.tab = t" :class="$store.billDrawer.tab === t ? 'segbtn active' : 'segbtn'" class="capitalize shrink-0" x-text="t"></button>
                                </template>
                            </div>

                            <div class="flex-1 space-y-5 p-5">
                                {{-- Overview --}}
                                <div x-show="$store.billDrawer.tab === 'overview'" class="space-y-4 text-sm">
                                    <p class="text-slate-600" x-text="$store.billDrawer.bill.description"></p>
                                    <div class="grid grid-cols-2 gap-y-2 rounded-xl bg-slate-50 p-4">
                                        <div><p class="text-xs text-slate-400">Due date</p><p class="font-medium text-slate-800" x-text="$store.billDrawer.bill.due_date"></p></div>
                                        <div><p class="text-xs text-slate-400">Category</p><p class="font-medium text-slate-800" x-text="$store.billDrawer.bill.category"></p></div>
                                        <div><p class="text-xs text-slate-400">Created by</p><p class="font-medium text-slate-800" x-text="$store.billDrawer.bill.created_by"></p></div>
                                        <div><p class="text-xs text-slate-400">Group</p><p class="font-medium text-slate-800" x-text="$store.billDrawer.bill.group || '—'"></p></div>
                                        <div><p class="text-xs text-slate-400">Split method</p><p class="font-medium text-slate-800" x-text="$store.billDrawer.bill.split_method"></p></div>
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between text-xs text-slate-500">
                                            <span>Collection Progress</span>
                                            <span x-text="Math.round(($store.billDrawer.bill.collected / $store.billDrawer.bill.amount) * 100) + '% Collected'"></span>
                                        </div>
                                        <div class="b-progress-track mt-1.5"><div class="b-progress-fill" :style="`width:${Math.round(($store.billDrawer.bill.collected / $store.billDrawer.bill.amount) * 100)}%; background:#2563EB;`"></div></div>
                                    </div>
                                </div>

                                {{-- Participants --}}
                                <div x-show="$store.billDrawer.tab === 'participants'" class="space-y-3">
                                    <template x-for="p in $store.billDrawer.bill.participants" :key="p.name">
                                        <div class="rounded-xl bg-slate-50 p-3.5 text-sm">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium text-slate-800" x-text="p.name"></span>
                                                <span class="badge" :class="p.status === 'Paid' ? 'bg-emerald-50 text-emerald-700' : (p.status === 'Partial' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600')" x-text="p.status"></span>
                                            </div>
                                            <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                                <span x-text="'Assigned ₦' + Number(p.assigned).toLocaleString()"></span>
                                                <span x-text="'Paid ₦' + Number(p.paid).toLocaleString()"></span>
                                            </div>
                                            <div class="mt-3 flex gap-2">
                                                <button type="button" @click="$store.paymentModal.bill = $store.billDrawer.bill; $store.paymentModal.participant = p.name; $store.paymentModal.open = true"
                                                        class="rounded-full bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Record Payment</button>
                                                <button type="button" class="rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300">Send Reminder</button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                {{-- Payments --}}
                                <div x-show="$store.billDrawer.tab === 'payments'" class="space-y-2">
                                    <template x-for="(pay, i) in $store.billDrawer.bill.payments" :key="i">
                                        <div class="flex items-center justify-between border-l-2 border-emerald-200 pl-3 text-sm">
                                            <span class="text-slate-700" x-text="pay.name"></span>
                                            <span class="font-mono text-slate-800" x-text="'₦' + Number(pay.amount).toLocaleString()"></span>
                                            <span class="text-xs text-slate-400" x-text="pay.date"></span>
                                        </div>
                                    </template>
                                    <p x-show="!$store.billDrawer.bill.payments || !$store.billDrawer.bill.payments.length" class="text-sm text-slate-400">No payments recorded yet.</p>
                                </div>

                                {{-- Activity --}}
                                <div x-show="$store.billDrawer.tab === 'activity'" class="space-y-2">
                                    <template x-for="(a, i) in $store.billDrawer.bill.activity" :key="i">
                                        <div class="flex items-center justify-between border-l-2 border-slate-100 pl-3 text-sm">
                                            <span class="text-slate-700" x-text="a.label"></span>
                                            <span class="text-xs text-slate-400" x-text="a.date"></span>
                                        </div>
                                    </template>
                                </div>

                                {{-- Settings --}}
                                <div x-show="$store.billDrawer.tab === 'settings'" class="space-y-2.5">
                                    <button @click="$store.billModal.mode='edit'; $store.billModal.data=$store.billDrawer.bill; $store.billModal.open=true; $store.billDrawer.open=false"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-left text-sm font-medium text-slate-700 hover:border-slate-300">Edit Bill</button>
                                    <button @click="$store.convertModal.bill = $store.billDrawer.bill; $store.convertModal.open = true"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-left text-sm font-medium text-slate-700 hover:border-slate-300">Convert to Expense</button>
                                    <button @click="$store.shareModal.bill = $store.billDrawer.bill; $store.shareModal.open = true"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-left text-sm font-medium text-slate-700 hover:border-slate-300">Generate Public Link</button>
                                    <button class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-left text-sm font-medium text-slate-700 hover:border-slate-300">Archive Bill</button>
                                    <button class="w-full rounded-xl border border-rose-200 px-4 py-2.5 text-left text-sm font-medium text-rose-600 hover:bg-rose-50">Delete Bill</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        {{-- ============ Record Payment modal ============ --}}
        <template x-teleport="body">
            <div x-show="$store.paymentModal.open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="$store.paymentModal.open = false">
                <div x-show="$store.paymentModal.open" x-transition.opacity class="absolute inset-0 bg-slate-900/40" @click="$store.paymentModal.open = false"></div>
                <div x-show="$store.paymentModal.open" x-transition x-data="{ amount: null, date: '', method: 'Cash', reference: '', notes: '' }"
                     class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                    <h3 class="font-display text-lg font-semibold text-slate-900">Record Payment</h3>
                    <p class="mt-0.5 text-sm text-slate-500" x-text="$store.paymentModal.participant ? ('For ' + $store.paymentModal.participant) : ''"></p>

                    <div class="mt-5 space-y-4">
                        <div><label class="field-label">Amount paid (₦)</label><input type="number" x-model.number="amount" class="field-input font-mono"></div>
                        <div><label class="field-label">Payment date</label><input type="date" x-model="date" class="field-input"></div>
                        <div>
                            <label class="field-label">Payment method</label>
                            <select x-model="method" class="field-input"><option>Cash</option><option>Transfer</option><option>Card</option><option>Wallet</option><option>Other</option></select>
                        </div>
                        <div><label class="field-label">Reference number</label><input type="text" x-model="reference" class="field-input"></div>
                        <div><label class="field-label">Upload receipt</label><input type="file" class="field-input"></div>
                        <div><label class="field-label">Notes</label><textarea x-model="notes" rows="2" class="field-input"></textarea></div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                        <button type="button" @click="$store.paymentModal.open = false" class="rounded-full px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                        <button type="button" @click="$store.paymentModal.open = false /* dispatch recordPayment here */" class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700">Record Payment</button>
                    </div>
                </div>
            </div>
        </template>

        {{-- ============ Share Bill modal ============ --}}
        <template x-teleport="body">
            <div x-show="$store.shareModal.open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="$store.shareModal.open = false">
                <div x-show="$store.shareModal.open" x-transition.opacity class="absolute inset-0 bg-slate-900/40" @click="$store.shareModal.open = false"></div>
                <div x-show="$store.shareModal.open" x-transition x-data="{ copied: false }" class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                    <h3 class="font-display text-lg font-semibold text-slate-900">Share Bill</h3>
                    <p class="mt-0.5 text-sm text-slate-500">Anyone with this link can view the bill but cannot edit it.</p>

                    <div class="mt-4 flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <span class="flex-1 truncate font-mono text-xs text-slate-600" x-text="$store.shareModal.bill?.public_link"></span>
                        <button type="button" @click="navigator.clipboard.writeText($store.shareModal.bill.public_link); copied = true; setTimeout(() => copied = false, 1500)"
                                class="shrink-0 rounded-full bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                            <span x-show="!copied">Copy Link</span><span x-show="copied">Copied!</span>
                        </button>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <a :href="'https://wa.me/?text=' + encodeURIComponent($store.shareModal.bill?.public_link || '')" target="_blank"
                           class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:border-slate-300">Share via WhatsApp</a>
                        <a :href="'mailto:?body=' + encodeURIComponent($store.shareModal.bill?.public_link || '')"
                           class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:border-slate-300">Share via Email</a>
                    </div>

                    <button type="button" @click="$store.shareModal.open = false" class="mt-6 w-full rounded-full px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-100">Close</button>
                </div>
            </div>
        </template>

        {{-- ============ Convert to Expense modal ============ --}}
        <template x-teleport="body">
            <div x-show="$store.convertModal.open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="$store.convertModal.open = false">
                <div x-show="$store.convertModal.open" x-transition.opacity class="absolute inset-0 bg-slate-900/40" @click="$store.convertModal.open = false"></div>
                <div x-show="$store.convertModal.open" x-transition x-data="convertForm()" class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">
                    <h3 class="font-display text-lg font-semibold text-slate-900">Convert Bill to Expense</h3>
                    <p class="mt-0.5 text-sm text-slate-500">Keep your Bills and Expenses in sync — no duplicate entry.</p>

                    <div class="mt-5 space-y-4">
                        <div>
                            <label class="field-label">Link to budget (optional)</label>
                            <select x-model="budget" class="field-input">
                                <option value="">No budget</option>
                                @foreach ($budgetsList ?? [] as $b) <option value="{{ $b->name ?? $b }}">{{ $b->name ?? $b }}</option> @endforeach
                            </select>
                        </div>

                        <div x-show="!budget">
                            <label class="field-label">Expense type</label>
                            <div class="mt-1.5 grid grid-cols-2 gap-2">
                                <button type="button" @click="type = 'Personal'" :class="type === 'Personal' ? 'segbtn active' : 'segbtn'">Personal</button>
                                <button type="button" @click="type = 'Group'" :class="type === 'Group' ? 'segbtn active' : 'segbtn'">Group</button>
                            </div>
                        </div>

                        <div>
                            <label class="field-label">Expense category</label>
                            <select x-model="category" class="field-input">
                                @foreach ($categoriesList as $c) <option>{{ $c }}</option> @endforeach
                            </select>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4 text-sm">
                            <div class="flex items-center justify-between"><span class="text-slate-500">Bill</span><span class="font-medium text-slate-800" x-text="$store.convertModal.bill?.name"></span></div>
                            <div class="mt-1.5 flex items-center justify-between"><span class="text-slate-500">Amount</span><span class="font-mono font-medium text-slate-800" x-text="'₦' + Number($store.convertModal.bill?.amount || 0).toLocaleString()"></span></div>
                            <div class="mt-1.5 flex items-center justify-between"><span class="text-slate-500">Group</span><span class="font-medium text-slate-800" x-text="budget ? 'Inferred from budget' : (type === 'Group' ? ($store.convertModal.bill?.group || '—') : 'None')"></span></div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                        <button type="button" @click="$store.convertModal.open = false" class="rounded-full px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                        <button type="button" @click="$store.convertModal.open = false /* dispatch convertToExpense here */" class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700">Confirm &amp; Save Expense</button>
                    </div>
                </div>
            </div>
        </template>

        {{-- ============ Create / Edit Bill modal ============ --}}
        <template x-teleport="body">
            <div x-show="$store.billModal.open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="$store.billModal.open = false">
                <div x-show="$store.billModal.open" x-transition.opacity class="absolute inset-0 bg-slate-900/40" @click="$store.billModal.open = false"></div>

                <div x-show="$store.billModal.open"
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-data="billForm()" x-init="init()"
                     class="relative max-h-[90vh] w-full max-w-4xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-8">

                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-display text-lg font-semibold text-slate-900" x-text="$store.billModal.mode === 'edit' ? 'Edit Bill' : 'Create Bill'"></h3>
                            <p class="mt-0.5 text-sm text-slate-500">Organized into a few quick sections.</p>
                        </div>
                        <button type="button" @click="$store.billModal.open = false" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form class="mt-6 space-y-8" @submit.prevent="submit()">

                        {{-- Section 1: Basic Information --}}
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Basic Information</h4>
                            <div class="mt-3 grid gap-4 sm:grid-cols-2">
                                <div class="sm:col-span-2"><label class="field-label">Bill name *</label><input type="text" x-model="form.name" required class="field-input" placeholder="e.g. Electricity Bill"></div>
                                <div><label class="field-label">Total amount (₦) *</label><input type="number" min="0" x-model.number="form.amount" required class="field-input font-mono"></div>
                                <div><label class="field-label">Due date *</label><input type="date" x-model="form.due_date" required class="field-input"></div>
                                <div>
                                    <label class="field-label">Category</label>
                                    <select x-model="form.category" class="field-input">
                                        @foreach ($categoriesList as $c) <option>{{ $c }}</option> @endforeach
                                    </select>
                                </div>
                                <div><label class="field-label">Notes</label><input type="text" x-model="form.notes" class="field-input"></div>
                                <div class="sm:col-span-2"><label class="field-label">Description</label><textarea x-model="form.description" rows="2" class="field-input"></textarea></div>
                            </div>
                        </section>

                        {{-- Section 2: Bill Type --}}
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Bill Type</h4>
                            <div class="mt-3 grid grid-cols-2 gap-2">
                                <button type="button" @click="form.type = 'Personal'" :class="form.type === 'Personal' ? 'segbtn active' : 'segbtn'">Personal Bill</button>
                                <button type="button" @click="form.type = 'Group'" :class="form.type === 'Group' ? 'segbtn active' : 'segbtn'">Group Bill</button>
                            </div>
                            <div x-show="form.type === 'Group'" x-transition class="mt-3">
                                <label class="field-label">Choose group</label>
                                <select x-model="form.group" class="field-input">
                                    <option value="" disabled>Select a group</option>
                                    @foreach ($groupsList as $g) <option>{{ $g }}</option> @endforeach
                                </select>
                            </div>
                        </section>

                        {{-- Section 3: Split Method --}}
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Split Method</h4>
                            <div class="mt-3 grid grid-cols-2 gap-2">
                                <button type="button" @click="form.split_method = 'Equal'" :class="form.split_method === 'Equal' ? 'segbtn active' : 'segbtn'">Equal Split</button>
                                <button type="button" @click="form.split_method = 'Fixed'" :class="form.split_method === 'Fixed' ? 'segbtn active' : 'segbtn'">Fixed Amount</button>
                            </div>

                            <div x-show="form.split_method === 'Equal' && form.participants.length" class="mt-3 rounded-xl bg-slate-50 p-3.5 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono font-semibold text-slate-800" x-text="'₦' + Number(form.amount || 0).toLocaleString()"></span>
                                    <span class="text-slate-500" x-text="form.participants.length + ' members'"></span>
                                    <span class="font-mono font-semibold text-blue-600" x-text="'₦' + equalShare().toLocaleString() + ' each'"></span>
                                </div>
                            </div>
                        </section>

                        {{-- Section 4: Participants --}}
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Participants</h4>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <template x-for="member in memberDirectory" :key="member">
                                    <button type="button" @click="toggleParticipant(member)" :class="isParticipant(member) ? 'segbtn active' : 'segbtn'" x-text="member"></button>
                                </template>
                            </div>

                            <div class="mt-3 flex flex-wrap items-end gap-2 rounded-xl border border-dashed border-slate-200 p-3">
                                <div class="flex-1 min-w-[120px]"><label class="field-label">Guest name</label><input type="text" x-model="guest.name" class="field-input" placeholder="Full name"></div>
                                <div class="flex-1 min-w-[120px]"><label class="field-label">Phone</label><input type="text" x-model="guest.phone" class="field-input"></div>
                                <div class="flex-1 min-w-[120px]"><label class="field-label">Email (optional)</label><input type="text" x-model="guest.email" class="field-input"></div>
                                <button type="button" @click="addGuest()" class="rounded-xl bg-slate-800 px-4 py-2.5 text-xs font-semibold text-white">Add Guest</button>
                            </div>

                            <div class="mt-3 space-y-2">
                                <template x-for="(p, i) in form.participants" :key="p.name">
                                    <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3 text-sm">
                                        <div>
                                            <span class="font-medium text-slate-800" x-text="p.name"></span>
                                            <span class="badge ml-2" :class="p.kind === 'Guest' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700'" x-text="p.kind"></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <template x-if="form.split_method === 'Equal'">
                                                <span class="font-mono text-slate-700" x-text="'₦' + equalShare().toLocaleString()"></span>
                                            </template>
                                            <template x-if="form.split_method === 'Fixed'">
                                                <input type="number" x-model.number="p.amount" class="field-input !mt-0 w-28 font-mono" placeholder="₦0.00">
                                            </template>
                                            <button type="button" @click="form.participants.splice(i, 1)" class="text-slate-400 hover:text-rose-600">✕</button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <p x-show="form.split_method === 'Fixed'" class="mt-2 text-xs" :class="remainingFixed() === 0 ? 'text-emerald-600' : 'text-amber-600'">
                                <span x-text="remainingFixed() === 0 ? 'Fully allocated.' : ('₦' + remainingFixed().toLocaleString() + ' remaining to allocate')"></span>
                            </p>
                        </section>

                        {{-- Section 5: Payment Settings --}}
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Payment Settings</h4>
                            <div class="mt-3 space-y-3">
                                <label class="flex items-center justify-between text-sm text-slate-700">
                                    Allow partial payments
                                    <span class="toggle" :class="form.allow_partial ? 'on' : ''" @click="form.allow_partial = !form.allow_partial"><span></span></span>
                                </label>
                                <label class="flex items-center justify-between text-sm text-slate-700">
                                    Allow upload payment proof
                                    <span class="toggle" :class="form.allow_proof ? 'on' : ''" @click="form.allow_proof = !form.allow_proof"><span></span></span>
                                </label>
                                <label class="flex items-center justify-between text-sm text-slate-700">
                                    Auto reminder
                                    <span class="toggle" :class="form.auto_reminder ? 'on' : ''" @click="form.auto_reminder = !form.auto_reminder"><span></span></span>
                                </label>
                                <div x-show="form.auto_reminder" x-transition>
                                    <label class="field-label">Reminder frequency</label>
                                    <select x-model="form.reminder_frequency" class="field-input"><option>Daily</option><option>Weekly</option><option>Monthly</option></select>
                                </div>
                            </div>
                        </section>

                        {{-- Section 6: Review --}}
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Review</h4>
                            <div class="mt-3 grid grid-cols-2 gap-y-2 rounded-xl bg-slate-50 p-4 text-sm sm:grid-cols-3">
                                <div><p class="text-xs text-slate-400">Bill</p><p class="font-medium text-slate-800" x-text="form.name || '—'"></p></div>
                                <div><p class="text-xs text-slate-400">Amount</p><p class="font-mono font-medium text-slate-800" x-text="'₦' + Number(form.amount || 0).toLocaleString()"></p></div>
                                <div><p class="text-xs text-slate-400">Group</p><p class="font-medium text-slate-800" x-text="form.type === 'Group' ? (form.group || '—') : 'None'"></p></div>
                                <div><p class="text-xs text-slate-400">Participants</p><p class="font-medium text-slate-800" x-text="form.participants.length"></p></div>
                                <div><p class="text-xs text-slate-400">Split</p><p class="font-medium text-slate-800" x-text="form.split_method"></p></div>
                                <div><p class="text-xs text-slate-400">Due</p><p class="font-medium text-slate-800" x-text="form.due_date || '—'"></p></div>
                            </div>
                        </section>

                        <p x-show="error" x-text="error" class="text-sm font-medium text-rose-600"></p>

                        <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-5">
                            <button type="button" @click="$store.billModal.open = false" class="rounded-full px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                            <template x-if="$store.billModal.mode === 'edit'">
                                <button type="button" @click="deleting = true" class="rounded-full border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50">Delete Bill</button>
                            </template>
                            <button type="button" @click="submit(true)" class="rounded-full border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:border-slate-300">Save Draft</button>
                            <button type="submit" :disabled="submitting" class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700 disabled:opacity-60">
                                <span x-show="!submitting" x-text="$store.billModal.mode === 'edit' ? 'Update Bill' : 'Create Bill'"></span>
                                <span x-show="submitting">Saving…</span>
                            </button>
                        </div>

                        <div x-show="deleting" x-cloak x-transition class="flex items-center justify-end gap-3 rounded-xl bg-rose-50 p-3.5 text-sm">
                            <span class="text-rose-700">Delete this bill permanently?</span>
                            <button type="button" @click="deleting = false" class="rounded-full px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-white">Cancel</button>
                            <button type="button" @click="deleting = false; $store.billModal.open = false /* dispatch delete here */" class="rounded-full bg-rose-600 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Yes, delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('billModal', { open: false, mode: 'create', data: null });
            Alpine.store('billDrawer', { open: false, bill: null, tab: 'overview' });
            Alpine.store('paymentModal', { open: false, bill: null, participant: null });
            Alpine.store('shareModal', { open: false, bill: null });
            Alpine.store('convertModal', { open: false, bill: null });

            Alpine.data('billsPage', () => ({
                loading: true,
                mobileFiltersOpen: false,
                datePresets: ['Today', 'Yesterday', 'This Week', 'Last Week', 'This Month', 'Last Month', 'Last 3 Months', 'This Year'],
                filters: { search: '', start: '', end: '', status: 'All', type: 'All', group: '', category: '', sort: 'newest' },
                sortLabels: { newest: 'Newest', oldest: 'Oldest', amount_desc: 'Highest Amount', amount_asc: 'Lowest Amount', due_date: 'Due Date' },

                init() {
                    setTimeout(() => { this.loading = false; }, 350);
                },

                applyPreset(label) {
                    const today = new Date();
                    const fmt = (d) => d.toISOString().slice(0, 10);
                    let start = new Date(today), end = new Date(today);
                    if (label === 'Yesterday') { start.setDate(today.getDate() - 1); end = new Date(start); }
                    if (label === 'This Week') { start.setDate(today.getDate() - today.getDay()); }
                    if (label === 'Last Week') { start.setDate(today.getDate() - today.getDay() - 7); end.setDate(today.getDate() - today.getDay() - 1); }
                    if (label === 'This Month') { start = new Date(today.getFullYear(), today.getMonth(), 1); }
                    if (label === 'Last Month') { start = new Date(today.getFullYear(), today.getMonth() - 1, 1); end = new Date(today.getFullYear(), today.getMonth(), 0); }
                    if (label === 'Last 3 Months') { start.setMonth(today.getMonth() - 3); }
                    if (label === 'This Year') { start = new Date(today.getFullYear(), 0, 1); }
                    this.filters.start = fmt(start);
                    this.filters.end = fmt(end);
                },

                isVisible(el) {
                    const name = el.dataset.name || '';
                    const tags = (el.dataset.tags || '').split('|');
                    const f = this.filters;

                    if (f.search && !name.includes(f.search.toLowerCase())) return false;
                    if (f.status !== 'All' && !tags.includes(f.status.toLowerCase())) return false;
                    if (f.type !== 'All' && !tags.includes(f.type.toLowerCase())) return false;
                    if (f.group && !tags.includes(f.group.toLowerCase())) return false;
                    if (f.category && !tags.includes(f.category.toLowerCase())) return false;
                    if (f.start && el.dataset.due < f.start) return false;
                    if (f.end && el.dataset.due > f.end) return false;

                    return true;
                },

                noResults() {
                    const cards = this.$el.querySelectorAll('[data-name]');
                    return cards.length > 0 && Array.from(cards).every(el => !this.isVisible(el));
                },
            }));

            Alpine.data('convertForm', () => ({
                budget: '', type: 'Personal', category: @json($categoriesList[0] ?? 'Others'),
            }));

            Alpine.data('billForm', () => ({
                submitting: false,
                deleting: false,
                error: '',
                memberDirectory: @json($memberDirectory),
                guest: { name: '', phone: '', email: '' },
                form: {},

                init() {
                    this.form = this.blank();
                    this.$watch('$store.billModal.data', (val) => {
                        this.form = val ? this.hydrate(val) : this.blank();
                        this.deleting = false;
                        this.error = '';
                    });
                },

                blank() {
                    return {
                        name: '', description: '', amount: null, due_date: '', category: @json($categoriesList[0] ?? 'Others'), notes: '',
                        type: 'Personal', group: '', split_method: 'Equal',
                        participants: [],
                        allow_partial: true, allow_proof: true, auto_reminder: true, reminder_frequency: 'Weekly',
                    };
                },

                hydrate(b) {
                    return {
                        name: b.name, description: b.description || '', amount: b.amount, due_date: b.due_date,
                        category: b.category, notes: b.notes || '',
                        type: b.type, group: b.group || '', split_method: b.split_method || 'Equal',
                        participants: (b.participants || []).map(p => ({ name: p.name, kind: p.kind, amount: p.assigned })),
                        allow_partial: b.allow_partial ?? true, allow_proof: b.allow_proof ?? true,
                        auto_reminder: b.auto_reminder ?? true, reminder_frequency: b.reminder_frequency || 'Weekly',
                    };
                },

                isParticipant(name) { return this.form.participants.some(p => p.name === name); },

                toggleParticipant(name) {
                    if (this.isParticipant(name)) {
                        this.form.participants = this.form.participants.filter(p => p.name !== name);
                    } else {
                        this.form.participants.push({ name, kind: 'Registered', amount: 0 });
                    }
                },

                addGuest() {
                    if (!this.guest.name) return;
                    this.form.participants.push({ name: this.guest.name, kind: 'Guest', amount: 0, phone: this.guest.phone, email: this.guest.email });
                    this.guest = { name: '', phone: '', email: '' };
                },

                equalShare() {
                    const n = this.form.participants.length || 1;
                    return Math.round((this.form.amount || 0) / n);
                },

                remainingFixed() {
                    const allocated = this.form.participants.reduce((sum, p) => sum + Number(p.amount || 0), 0);
                    return (this.form.amount || 0) - allocated;
                },

                submit(draft = false) {
                    if (!draft && (!this.form.name || !this.form.amount || !this.form.due_date)) {
                        this.error = 'Please fill in all required fields.';
                        return;
                    }
                    this.error = '';
                    this.submitting = true;

                    // Replace with a real call, e.g.:
                    // Livewire.dispatch($store.billModal.mode === 'edit' ? 'updateBill' : 'createBill', { ...this.form, draft })
                    setTimeout(() => {
                        this.submitting = false;
                        this.$store.billModal.open = false;
                    }, 600);
                },
            }));
        });
    </script>
</x-app-layout>