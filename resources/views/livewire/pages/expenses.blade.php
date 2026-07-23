<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new 
#[Layout('layouts.app')]
#[Title('Expenses')]
class extends Component {
    public function with(): array
    {
        $groupsList = ['Family', 'Church cooperative', 'Office savings'];
        $budgetsList = [
            (object)['name' => 'Food', 'type' => 'Personal', 'remaining' => 15000, 'limit' => 60000, 'spent' => 45000],
            (object)['name' => 'Family utilities', 'type' => 'Group', 'group' => 'Family', 'remaining' => 18000, 'limit' => 50000, 'spent' => 32000],
            (object)['name' => 'Transport', 'type' => 'Personal', 'remaining' => 7000, 'limit' => 25000, 'spent' => 18000],
        ];
        $categoriesList = ['Food', 'Rent', 'Transport', 'Shopping', 'Utilities', 'Others'];
        $membersList = ['Father', 'Mother', 'Mary', 'John', 'Peter'];

        $expenses = collect([
            (object)[
                'id' => 1, 'name' => 'Rice (bag)', 'amount' => 32000, 'category' => 'Food',
                'type' => 'Personal', 'budget' => 'Food', 'group' => null,
                'recorded_by' => 'Mary', 'paid_by' => 'Father', 'date' => '2026-07-15',
                'description' => 'Bag of rice for the month.', 'notes' => 'Bought at Shoprite.',
                'badges' => ['Personal', 'Budgeted', 'Approved'], 'remaining_budget' => 15000, 'budget_progress' => 75,
                'members' => [], 'split_type' => null, 'participants' => [],
                'attachments' => ['receipt-rice.jpg'],
                'history' => [
                    ['label' => 'Created by Mary', 'date' => 'Jul 15, 9:02 AM'],
                    ['label' => 'Approved by Father', 'date' => 'Jul 15, 10:30 AM'],
                ],
            ],
            (object)[
                'id' => 2, 'name' => 'Electricity Bill', 'amount' => 15000, 'category' => 'Utilities',
                'type' => 'Group', 'budget' => 'Family utilities', 'group' => 'Family',
                'recorded_by' => 'Mary', 'paid_by' => 'Father', 'date' => '2026-07-14',
                'description' => 'NEPA bill for July.', 'notes' => '',
                'badges' => ['Group', 'Budgeted', 'Approved'], 'remaining_budget' => 18000, 'budget_progress' => 64,
                'members' => ['Father', 'Mother', 'Mary', 'John'], 'split_type' => 'Equal',
                'participants' => [
                    ['name' => 'Father', 'role' => 'Paid'], ['name' => 'Mother', 'role' => 'Benefited'],
                    ['name' => 'Mary', 'role' => 'Benefited'], ['name' => 'John', 'role' => 'Benefited'],
                ],
                'attachments' => [],
                'history' => [['label' => 'Added by Mary', 'date' => 'Jul 14, 6:12 PM']],
            ],
            (object)[
                'id' => 3, 'name' => 'Fuel', 'amount' => 8000, 'category' => 'Transport',
                'type' => 'Personal', 'budget' => 'Transport', 'group' => null,
                'recorded_by' => 'John', 'paid_by' => 'John', 'date' => '2026-07-13',
                'description' => 'Fuel for the week.', 'notes' => '',
                'badges' => ['Personal', 'Budgeted', 'Approved'], 'remaining_budget' => 7000, 'budget_progress' => 72,
                'members' => [], 'split_type' => null, 'participants' => [],
                'attachments' => [], 'history' => [
                    ['label' => 'Added by John', 'date' => 'Jul 13, 8:00 AM'],
                    ['label' => 'Edited amount by John', 'date' => 'Jul 13 (Monday)'],
                ],
            ],
            (object)[
                'id' => 4, 'name' => 'Dinner Out', 'amount' => 80000, 'category' => 'Food',
                'type' => 'Group', 'budget' => null, 'group' => 'Family',
                'recorded_by' => 'Ada', 'paid_by' => 'Ada', 'date' => '2026-07-12',
                'description' => 'Family dinner at Circle Mall.', 'notes' => 'Split evenly among four.',
                'badges' => ['Group', 'Unbudgeted', 'Reimbursable'], 'remaining_budget' => null, 'budget_progress' => null,
                'members' => ['Ada', 'Bayo', 'Chika', 'Dele'], 'split_type' => 'Equal',
                'participants' => [
                    ['name' => 'Ada', 'role' => 'Paid'], ['name' => 'Bayo', 'role' => 'Benefited'],
                    ['name' => 'Chika', 'role' => 'Benefited'], ['name' => 'Dele', 'role' => 'Benefited'],
                ],
                'attachments' => ['receipt-dinner.pdf'],
                'history' => [['label' => 'Added by Ada', 'date' => 'Jul 12, 8:45 PM']],
            ],
            (object)[
                'id' => 5, 'name' => 'Data subscription', 'amount' => 15000, 'category' => 'Others',
                'type' => 'Personal', 'budget' => null, 'group' => null,
                'recorded_by' => 'You', 'paid_by' => 'You', 'date' => '2026-07-10',
                'description' => 'Monthly internet data plan.', 'notes' => '',
                'badges' => ['Personal', 'Unbudgeted'], 'remaining_budget' => null, 'budget_progress' => null,
                'members' => [], 'split_type' => null, 'participants' => [],
                'attachments' => [], 'history' => [['label' => 'Added by You', 'date' => 'Jul 10, 7:15 AM']],
            ],
        ]);

        $totalExpenses = $expenses->count();
        $totalSpent = $expenses->sum('amount');
        $personalTotal = $expenses->where('type', 'Personal')->sum('amount');
        $groupTotal = $expenses->where('type', 'Group')->sum('amount');
        $budgetedTotal = $expenses->whereNotNull('budget')->sum('amount');
        $unbudgetedTotal = $expenses->whereNull('budget')->sum('amount');

        $summaryCards = [
            ['label' => 'Total Expenses', 'value' => $totalExpenses, 'trend' => '+3 this month', 'up' => true, 'icon' => 'list'],
            ['label' => 'Total Spent This Month', 'value' => '₦' . number_format($totalSpent), 'trend' => '↑ 12% vs last month', 'up' => true, 'icon' => 'trend-up'],
            ['label' => 'Personal Expenses', 'value' => '₦' . number_format($personalTotal), 'trend' => '↑ 4% vs last month', 'up' => true, 'icon' => 'user'],
            ['label' => 'Group Expenses', 'value' => '₦' . number_format($groupTotal), 'trend' => '↑ 20% vs last month', 'up' => true, 'icon' => 'users'],
            ['label' => 'Budgeted Expenses', 'value' => '₦' . number_format($budgetedTotal), 'trend' => 'Within plan', 'up' => null, 'icon' => 'wallet'],
            ['label' => 'Unbudgeted Expenses', 'value' => '₦' . number_format($unbudgetedTotal), 'trend' => '18 not linked', 'up' => false, 'icon' => 'alert'],
        ];

        $attentionItems = collect([
            (object)['label' => 'Food Budget', 'meta' => '95% used', 'tone' => 'orange'],
            (object)['label' => '4 expenses missing receipts', 'meta' => 'Attach proof of payment', 'tone' => 'blue'],
            (object)['label' => '3 expenses waiting approval', 'meta' => 'Review pending items', 'tone' => 'blue'],
            (object)['label' => '2 group expenses need review', 'meta' => 'Confirm participants & split', 'tone' => 'red'],
        ]);

        $insights = [
            'Food accounts for 38% of your expenses.',
            'You spent 10% less than last month.',
            'Most expenses belong to Family Budget.',
            'Transport spending has increased.',
            '18 expenses are not linked to a budget.',
        ];

        $timeline = collect([
            (object)['day' => 'Today', 'text' => 'Rice Purchased — ₦18,000'],
            (object)['day' => 'Yesterday', 'text' => 'Mary Added Expense'],
            (object)['day' => 'Monday', 'text' => 'John Edited Fuel Expense'],
        ]);

        $toneClasses = [
            'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
            'orange' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
            'red' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600'],
            'green' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
        ];
        $badgeTone = [
            'Personal' => 'bg-slate-100 text-slate-600', 'Group' => 'bg-blue-50 text-blue-700',
            'Budgeted' => 'bg-emerald-50 text-emerald-700', 'Unbudgeted' => 'bg-orange-50 text-orange-700',
            'Planned' => 'bg-purple-50 text-purple-700', 'Reimbursable' => 'bg-indigo-50 text-indigo-700',
            'Approved' => 'bg-emerald-50 text-emerald-700',
        ];
        $avatarColors = ['bg-blue-500', 'bg-emerald-500', 'bg-orange-500', 'bg-purple-500', 'bg-rose-500'];

        return compact(
            'groupsList', 'budgetsList', 'categoriesList', 'membersList', 'expenses',
            'totalExpenses', 'totalSpent', 'personalTotal', 'groupTotal', 'budgetedTotal',
            'unbudgetedTotal', 'summaryCards', 'attentionItems', 'insights', 'timeline',
            'toneClasses', 'badgeTone', 'avatarColors'
        );
    }
}; ?>

<div>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4" x-data="{}">
            <div>
                <h2 class="font-display text-xl font-semibold leading-tight text-slate-900">Expenses</h2>
                <p class="mt-0.5 text-sm text-slate-500">Track and manage all your personal and shared expenses.</p>
            </div>
            <button type="button" @click="$store.expenseModal.mode = 'create'; $store.expenseModal.data = null; $store.expenseModal.open = true"
                    class="hidden items-center gap-2 rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 transition hover:bg-blue-700 sm:inline-flex">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
                Add Expense
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
        .skeleton{ background: linear-gradient(90deg,#EEF1F6 25%,#F6F8FA 37%,#EEF1F6 63%); background-size:400% 100%; animation: shimmer 1.4s ease infinite; border-radius:16px; }
        @keyframes shimmer{ 0%{ background-position:100% 50%;} 100%{ background-position:0 50%;} }
    </style>

    <div x-data="expensesPage()" x-init="init()" x-cloak class="relative mx-auto max-w-7xl space-y-8 pb-20">

        {{-- Floating Add Expense button (mobile) --}}
        <button type="button" @click="$store.expenseModal.mode = 'create'; $store.expenseModal.data = null; $store.expenseModal.open = true"
                class="fixed bottom-6 right-6 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-300 sm:hidden">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
        </button>

        @if ($expenses->isEmpty())
            <div class="b-card flex flex-col items-center gap-4 px-6 py-20 text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h13l3 4v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Z"/><path d="M8 11h8M8 15h5"/></svg>
                </div>
                <div>
                    <p class="font-display text-lg font-semibold text-slate-900">No expenses yet.</p>
                    <p class="mt-1 text-sm text-slate-500">Create your first expense.</p>
                </div>
                <button type="button" @click="$store.expenseModal.mode = 'create'; $store.expenseModal.data = null; $store.expenseModal.open = true"
                        class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700">Add Expense</button>
            </div>
        @else

            {{-- Summary cards --}}
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($summaryCards as $card)
                    <div class="b-card p-5">
                        <div class="flex items-center justify-between">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                @switch($card['icon'])
                                    @case('list')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                        @break
                                    @case('trend-up')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 17 9 11l4 4 8-8M21 7h-6v6"/></svg>
                                        @break
                                    @case('user')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c1-3.5 4-5.5 7-5.5s6 2 7 5.5"/></svg>
                                        @break
                                    @case('users')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.5"/><path d="M3.5 19c.7-3 3-5 5.5-5s4.8 2 5.5 5M14.5 19c.3-2 1.6-3.6 3.3-4.3"/></svg>
                                        @break
                                    @case('wallet')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7a2 2 0 0 1 2-2h13a1 1 0 0 1 1 1v3"/><path d="M3 7v11a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-5a2 2 0 1 0 0 4"/></svg>
                                        @break
                                    @default
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10.3 3.9 1.8 18a1.5 1.5 0 0 0 1.3 2.2h17.8a1.5 1.5 0 0 0 1.3-2.2L13.7 3.9a1.5 1.5 0 0 0-2.6 0Z"/><path d="M12 9v4M12 17h.01"/></svg>
                                @endswitch
                            </span>
                        </div>
                        <p class="font-mono mt-4 text-2xl font-semibold text-slate-900">{{ $card['value'] }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-500">{{ $card['label'] }}</p>
                        @if (!is_null($card['up']))
                            <p class="mt-2 text-xs font-medium {{ $card['up'] ? 'text-emerald-600' : 'text-rose-600' }}">{{ $card['trend'] }} <span class="text-slate-400 font-normal">· vs last month</span></p>
                        @else
                            <p class="mt-2 text-xs font-medium text-slate-400">{{ $card['trend'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Sticky filter toolbar --}}
            <div class="sticky top-[73px] z-30 -mx-2 rounded-2xl border border-slate-200 bg-white/95 p-3 backdrop-blur-md sm:mx-0">
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" x-model="filters.search" placeholder="Search title, category, budget, group, member..."
                               class="w-full rounded-full border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-4 text-sm focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    <select x-model="filters.duration" class="hidden rounded-full border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-600 sm:block">
                        <option>Today</option><option>Yesterday</option><option>This Week</option><option>Last Week</option>
                        <option selected>This Month</option><option>Last Month</option><option>Last 3 Months</option>
                        <option>Last 6 Months</option><option>This Year</option><option>Custom Date Range</option>
                    </select>
                    <button type="button" @click="mobileFiltersOpen = true" class="flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-600 sm:hidden">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
                        Filters
                    </button>
                </div>

                {{-- Desktop filter row --}}
                <div class="mt-3 hidden flex-wrap items-center gap-2 sm:flex">
                    <template x-for="opt in ['All','Personal','Group']" :key="opt">
                        <button type="button" @click="filters.type = opt" :class="filters.type === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                    </template>
                    <span class="mx-1 h-5 w-px bg-slate-200"></span>
                    <template x-for="opt in ['All','Budgeted','Without Budget']" :key="opt">
                        <button type="button" @click="filters.budget = opt" :class="filters.budget === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                    </template>

                    <select x-show="filters.type === 'Group'" x-model="filters.group" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">
                        <option value="">All groups</option>
                        @foreach ($groupsList as $g) <option>{{ $g }}</option> @endforeach
                    </select>

                    <select x-model="filters.category" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">
                        <option value="">All categories</option>
                        @foreach ($categoriesList as $c) <option>{{ $c }}</option> @endforeach
                    </select>

                    <select x-model="filters.member" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">
                        <option value="">All members</option>
                        @foreach ($membersList as $m) <option>{{ $m }}</option> @endforeach
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
                            <div>
                                <label class="field-label">Duration</label>
                                <select x-model="filters.duration" class="field-input">
                                    <option>Today</option><option>Yesterday</option><option>This Week</option><option>Last Week</option>
                                    <option selected>This Month</option><option>Last Month</option><option>Last 3 Months</option>
                                    <option>Last 6 Months</option><option>This Year</option><option>Custom Date Range</option>
                                </select>
                            </div>
                            <div>
                                <label class="field-label">Expense Type</label>
                                <div class="mt-1.5 grid grid-cols-3 gap-2">
                                    <template x-for="opt in ['All','Personal','Group']" :key="opt">
                                        <button type="button" @click="filters.type = opt" :class="filters.type === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <label class="field-label">Budget</label>
                                <div class="mt-1.5 grid grid-cols-3 gap-2">
                                    <template x-for="opt in ['All','Budgeted','Without Budget']" :key="opt">
                                        <button type="button" @click="filters.budget = opt" :class="filters.budget === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
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
                                <label class="field-label">Member</label>
                                <select x-model="filters.member" class="field-input">
                                    <option value="">All members</option>
                                    @foreach ($membersList as $m) <option>{{ $m }}</option> @endforeach
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

            {{-- Expense list + side column --}}
            <div class="grid gap-6 xl:grid-cols-[1fr_320px]">

                {{-- Loading skeleton --}}
                <div x-show="loading" class="space-y-4">
                    <div class="skeleton h-32"></div>
                    <div class="skeleton h-32"></div>
                    <div class="skeleton h-32"></div>
                </div>

                <div x-show="!loading" class="space-y-4">
                    @foreach ($expenses as $e)
                        @php
                            $tags = collect([$e->type, $e->budget ? 'Budgeted' : 'Without Budget', $e->group, $e->category])->filter()->map(fn($t)=>strtolower($t))->implode('|');
                        @endphp
                        <div class="b-card p-5" x-data="{ expanded: false, tab: 'general', confirmDelete: false }"
                             data-name="{{ strtolower($e->name) }}" data-tags="{{ $tags }}"
                             data-amount="{{ $e->amount }}" data-date="{{ $e->date }}"
                             x-show="isVisible($el)">

                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-display text-base font-semibold text-slate-900">{{ $e->name }}</h3>
                                        <span class="font-mono text-sm font-semibold text-slate-700">₦{{ number_format($e->amount) }}</span>
                                    </div>
                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        @foreach ($e->badges as $b)
                                            <span class="badge {{ $badgeTone[$b] ?? 'bg-slate-100 text-slate-600' }}">{{ $b }}</span>
                                        @endforeach
                                    </div>
                                </div>

                                @if ($e->members)
                                    <div class="flex -space-x-2">
                                        @foreach (array_slice($e->members, 0, 3) as $i => $m)
                                            <span class="avatar {{ $avatarColors[$i % count($avatarColors)] }} text-white">{{ strtoupper(substr($m,0,1)) }}</span>
                                        @endforeach
                                        @if (count($e->members) > 3)
                                            <span class="avatar bg-slate-200 text-slate-600">+{{ count($e->members) - 3 }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500">
                                <span>{{ $e->category }}</span>
                                @if ($e->budget)<span>{{ $e->budget }} Budget</span>@endif
                                @if ($e->group)<span>{{ $e->group }} Group</span>@endif
                                <span>Recorded by {{ $e->recorded_by }}</span>
                                <span>Paid by {{ $e->paid_by }}</span>
                                <span>{{ \Carbon\Carbon::parse($e->date)->diffForHumans() }}</span>
                            </div>

                            {{-- Actions --}}
                            <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3" x-show="!confirmDelete">
                                <button type="button" @click="expanded = !expanded" class="rounded-full border border-slate-200 px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300">
                                    <span x-text="expanded ? 'Hide' : 'View'"></span>
                                </button>
                                <button type="button" @click="$store.expenseModal.mode='edit'; $store.expenseModal.data=@js($e); $store.expenseModal.open=true"
                                        class="rounded-full border border-slate-200 px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300">Edit</button>
                                <button type="button" @click="confirmDelete = true" class="rounded-full border border-rose-200 px-3.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                            </div>
                            <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3 text-sm" x-show="confirmDelete" x-cloak>
                                <span class="text-slate-600">Delete this expense?</span>
                                <button type="button" @click="confirmDelete = false" class="ml-auto rounded-full px-3.5 py-1.5 text-xs font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                                <button type="button" @click="confirmDelete = false" class="rounded-full bg-rose-600 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Yes, delete</button>
                            </div>

                            {{-- Expandable detail panel --}}
                            <div x-show="expanded" x-transition class="mt-4 border-t border-slate-100 pt-4">
                                <div class="flex flex-wrap gap-1.5">
                                    <button type="button" @click="tab = 'general'" :class="tab === 'general' ? 'segbtn active' : 'segbtn'">General</button>
                                    @if ($e->budget)<button type="button" @click="tab = 'budget'" :class="tab === 'budget' ? 'segbtn active' : 'segbtn'">Budget</button>@endif
                                    @if ($e->group)<button type="button" @click="tab = 'group'" :class="tab === 'group' ? 'segbtn active' : 'segbtn'">Group</button>@endif
                                    <button type="button" @click="tab = 'attachments'" :class="tab === 'attachments' ? 'segbtn active' : 'segbtn'">Attachments</button>
                                    <button type="button" @click="tab = 'history'" :class="tab === 'history' ? 'segbtn active' : 'segbtn'">History</button>
                                </div>

                                <div class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm">
                                    <div x-show="tab === 'general'" class="space-y-2 text-slate-600">
                                        <p><span class="font-medium text-slate-800">Description:</span> {{ $e->description ?: '—' }}</p>
                                        <p><span class="font-medium text-slate-800">Notes:</span> {{ $e->notes ?: '—' }}</p>
                                        <p><span class="font-medium text-slate-800">Date:</span> {{ \Carbon\Carbon::parse($e->date)->format('M j, Y') }}</p>
                                    </div>

                                    @if ($e->budget)
                                        <div x-show="tab === 'budget'" class="space-y-3 text-slate-600">
                                            <p><span class="font-medium text-slate-800">Budget:</span> {{ $e->budget }}</p>
                                            <p><span class="font-medium text-slate-800">Remaining:</span> ₦{{ number_format($e->remaining_budget) }}</p>
                                            <div class="b-progress-track"><div class="b-progress-fill" style="width:{{ $e->budget_progress }}%; background:#2563EB;"></div></div>
                                        </div>
                                    @endif

                                    @if ($e->group)
                                        <div x-show="tab === 'group'" class="space-y-2 text-slate-600">
                                            <p><span class="font-medium text-slate-800">Group:</span> {{ $e->group }}</p>
                                            <p><span class="font-medium text-slate-800">Split type:</span> {{ $e->split_type ?? '—' }}</p>
                                            <div class="mt-2 space-y-1.5">
                                                @foreach ($e->participants as $p)
                                                    <div class="flex items-center justify-between">
                                                        <span>{{ $p['name'] }}</span>
                                                        <span class="badge {{ $p['role'] === 'Paid' ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-600' }}">{{ $p['role'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div x-show="tab === 'attachments'" class="space-y-2 text-slate-600">
                                        @forelse ($e->attachments as $a)
                                            <div class="flex items-center gap-2"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12V7a2 2 0 0 0-2-2H8L4 9v11a2 2 0 0 0 2 2h13a2 2 0 0 0 2-2v-3"/></svg> {{ $a }}</div>
                                        @empty
                                            <p class="text-slate-400">No attachments.</p>
                                        @endforelse
                                    </div>

                                    <div x-show="tab === 'history'" class="space-y-2 text-slate-600">
                                        @foreach ($e->history as $h)
                                            <div class="flex items-center justify-between"><span>{{ $h['label'] }}</span><span class="text-xs text-slate-400">{{ $h['date'] }}</span></div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <p x-show="noResults()" class="b-card p-8 text-center text-sm text-slate-500">No expenses match your filters.</p>
                </div>

                {{-- Insights + Timeline --}}
                <div class="space-y-6">
                    <div class="b-card p-5">
                        <h3 class="font-display text-sm font-semibold text-slate-900">Insights</h3>
                        <ul class="mt-3 space-y-2.5">
                            @foreach ($insights as $insight)
                                <li class="flex items-start gap-2 text-sm text-slate-600">
                                    <svg class="mt-0.5 shrink-0 text-blue-500" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 3 21h18L12 2Z"/></svg>
                                    {{ $insight }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="b-card p-5">
                        <h3 class="font-display text-sm font-semibold text-slate-900">Recent Activity</h3>
                        <div class="mt-3 space-y-4">
                            @foreach ($timeline->groupBy('day') as $day => $items)
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $day }}</p>
                                    <div class="mt-1.5 space-y-1.5 border-l-2 border-slate-100 pl-3">
                                        @foreach ($items as $entry)
                                            <p class="text-sm text-slate-600">{{ $entry->text }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Add / Edit Expense modal --}}
        <template x-teleport="body">
            <div x-show="$store.expenseModal.open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="$store.expenseModal.open = false">
                <div x-show="$store.expenseModal.open" x-transition.opacity class="absolute inset-0 bg-slate-900/40" @click="$store.expenseModal.open = false"></div>

                <div x-show="$store.expenseModal.open"
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     x-data="expenseForm()" x-init="init()"
                     class="relative max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-8">

                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-display text-lg font-semibold text-slate-900" x-text="$store.expenseModal.mode === 'edit' ? 'Edit Expense' : 'Add Expense'"></h3>
                            <p class="mt-0.5 text-sm text-slate-500">Fill in the details below — organized in a few quick sections.</p>
                        </div>
                        <button type="button" @click="$store.expenseModal.open = false" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form class="mt-6 space-y-8" @submit.prevent="submit()">

                        {{-- Section 1: Basic information --}}
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Basic Information</h4>
                            <div class="mt-3 grid gap-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label class="field-label">Expense name *</label>
                                    <input type="text" x-model="form.name" required class="field-input" placeholder="e.g. Rice">
                                </div>
                                <div>
                                    <label class="field-label">Amount (₦) *</label>
                                    <input type="number" min="0" x-model.number="form.amount" required class="field-input font-mono" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="field-label">Date *</label>
                                    <input type="date" x-model="form.date" required class="field-input">
                                </div>
                                <div>
                                    <label class="field-label">Category *</label>
                                    <select x-model="form.category" required class="field-input">
                                        <option value="" disabled>Select category</option>
                                        @foreach ($categoriesList as $c) <option>{{ $c }}</option> @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="field-label">Notes</label>
                                    <input type="text" x-model="form.notes" class="field-input" placeholder="Optional">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="field-label">Description</label>
                                    <textarea x-model="form.description" rows="2" class="field-input"></textarea>
                                </div>
                            </div>
                        </section>

                        {{-- Section 2: Budget --}}
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Budget</h4>
                            <div class="mt-3 grid grid-cols-2 gap-2">
                                <button type="button" @click="form.budgetMode = 'linked'" :class="form.budgetMode === 'linked' ? 'segbtn active' : 'segbtn'">Link to Budget</button>
                                <button type="button" @click="form.budgetMode = 'none'" :class="form.budgetMode === 'none' ? 'segbtn active' : 'segbtn'">No Budget</button>
                            </div>

                            <div x-show="form.budgetMode === 'linked'" x-transition class="mt-4">
                                <label class="field-label">Choose budget</label>
                                <select x-model="form.budget" class="field-input">
                                    <option value="" disabled>Select a budget</option>
                                    @foreach ($budgetsList as $b) <option value="{{ $b->name }}">{{ $b->name }}</option> @endforeach
                                </select>

                                <template x-if="selectedBudget">
                                    <div class="mt-3 rounded-xl bg-slate-50 p-3.5 text-sm">
                                        <div class="flex items-center justify-between"><span class="text-slate-500">Budget</span><span class="font-medium text-slate-800" x-text="selectedBudget.name"></span></div>
                                        <div class="mt-1.5 flex items-center justify-between"><span class="text-slate-500">Type</span><span class="font-medium text-slate-800" x-text="selectedBudget.type"></span></div>
                                        <div class="mt-1.5 flex items-center justify-between"><span class="text-slate-500">Remaining</span><span class="font-mono font-semibold text-emerald-600" x-text="'₦' + Number(selectedBudget.remaining).toLocaleString()"></span></div>
                                    </div>
                                </template>
                            </div>

                            <div x-show="form.budgetMode === 'none'" x-transition class="mt-4 space-y-4">
                                <div>
                                    <label class="field-label">Expense type</label>
                                    <div class="mt-1.5 grid grid-cols-2 gap-2">
                                        <button type="button" @click="form.type = 'Personal'" :class="form.type === 'Personal' ? 'segbtn active' : 'segbtn'">Personal</button>
                                        <button type="button" @click="form.type = 'Group'" :class="form.type === 'Group' ? 'segbtn active' : 'segbtn'">Group</button>
                                    </div>
                                </div>
                                <div x-show="form.type === 'Group'" x-transition>
                                    <label class="field-label">Choose group</label>
                                    <select x-model="form.group" class="field-input">
                                        <option value="" disabled>Select a group</option>
                                        @foreach ($groupsList as $g) <option>{{ $g }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                        </section>

                        {{-- Section 3: Payment --}}
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Payment</h4>
                            <div class="mt-3 grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="field-label">Paid by</label>
                                    <select x-model="form.paidBy" class="field-input">
                                        <option value="" disabled>Select payer</option>
                                        @foreach ($membersList as $m) <option>{{ $m }}</option> @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="field-label">Payment method</label>
                                    <select x-model="form.paymentMethod" class="field-input">
                                        <option>Cash</option><option>Transfer</option><option>Card</option><option>Wallet</option><option>Other</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="field-label">Receipt upload</label>
                                    <input type="file" @change="form.receiptName = $event.target.files[0]?.name || ''" class="field-input">
                                </div>
                            </div>
                        </section>

                        {{-- Section 4: Split --}}
                        <section x-show="isGroupExpense">
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Split Expense (Optional)</h4>
                            <label class="mt-3 flex items-center gap-2 text-sm text-slate-700">
                                <input type="checkbox" x-model="form.split.enabled" class="h-4 w-4 rounded border-slate-300 text-blue-600">
                                Split this expense
                            </label>

                            <div x-show="form.split.enabled" x-transition class="mt-3 space-y-4">
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" @click="form.split.type = 'Equal'" :class="form.split.type === 'Equal' ? 'segbtn active' : 'segbtn'">Equal</button>
                                    <button type="button" @click="form.split.type = 'Fixed'" :class="form.split.type === 'Fixed' ? 'segbtn active' : 'segbtn'">Fixed Amount</button>
                                </div>

                                <div>
                                    <label class="field-label">Choose participants</label>
                                    <div class="mt-1.5 flex flex-wrap gap-2">
                                        <template x-for="member in participantOptions" :key="member">
                                            <button type="button" @click="toggleParticipant(member)"
                                                    :class="form.split.participants.includes(member) ? 'segbtn active' : 'segbtn'" x-text="member"></button>
                                        </template>
                                    </div>
                                </div>

                                <div x-show="form.split.participants.length" class="rounded-xl bg-slate-50 p-3.5 text-sm">
                                    <template x-for="p in form.split.participants" :key="p">
                                        <div class="flex items-center justify-between py-1">
                                            <span class="text-slate-600" x-text="p"></span>
                                            <span class="font-mono text-slate-800" x-text="'₦' + equalShare().toLocaleString()"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </section>

                        {{-- Section 5: Review --}}
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Review</h4>
                            <div class="mt-3 rounded-xl bg-slate-50 p-4 text-sm">
                                <div class="grid grid-cols-2 gap-y-2 sm:grid-cols-3">
                                    <div><p class="text-xs text-slate-400">Expense</p><p class="font-medium text-slate-800" x-text="form.name || '—'"></p></div>
                                    <div><p class="text-xs text-slate-400">Amount</p><p class="font-mono font-medium text-slate-800" x-text="'₦' + Number(form.amount || 0).toLocaleString()"></p></div>
                                    <div><p class="text-xs text-slate-400">Category</p><p class="font-medium text-slate-800" x-text="form.category || '—'"></p></div>
                                    <div><p class="text-xs text-slate-400">Budget</p><p class="font-medium text-slate-800" x-text="form.budgetMode === 'linked' ? (form.budget || '—') : 'None'"></p></div>
                                    <div><p class="text-xs text-slate-400">Group</p><p class="font-medium text-slate-800" x-text="resolvedGroup || '—'"></p></div>
                                    <div><p class="text-xs text-slate-400">Paid by</p><p class="font-medium text-slate-800" x-text="form.paidBy || '—'"></p></div>
                                    <div><p class="text-xs text-slate-400">Split</p><p class="font-medium text-slate-800" x-text="form.split.enabled ? form.split.type : 'No split'"></p></div>
                                </div>
                            </div>
                        </section>

                        <p x-show="error" x-text="error" class="text-sm font-medium text-rose-600"></p>

                        <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-5">
                            <button type="button" @click="$store.expenseModal.open = false" class="rounded-full px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                            <template x-if="$store.expenseModal.mode === 'edit'">
                                <button type="button" @click="deleting = true" class="rounded-full border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50">Delete Expense</button>
                            </template>
                            <button type="button" @click="submit(true)" class="rounded-full border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:border-slate-300">Save Draft</button>
                            <button type="submit" :disabled="submitting" class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700 disabled:opacity-60">
                                <span x-show="!submitting" x-text="$store.expenseModal.mode === 'edit' ? 'Update Expense' : 'Save Expense'"></span>
                                <span x-show="submitting">Saving…</span>
                            </button>
                        </div>

                        {{-- Inline delete confirmation --}}
                        <div x-show="deleting" x-cloak x-transition class="flex items-center justify-end gap-3 rounded-xl bg-rose-50 p-3.5 text-sm">
                            <span class="text-rose-700">Delete this expense permanently?</span>
                            <button type="button" @click="deleting = false" class="rounded-full px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-white">Cancel</button>
                            <button type="button" @click="deleting = false; $store.expenseModal.open = false" class="rounded-full bg-rose-600 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Yes, delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('expenseModal', { open: false, mode: 'create', data: null });

            Alpine.data('expensesPage', () => ({
                loading: true,
                mobileFiltersOpen: false,
                filters: { search: '', duration: 'This Month', type: 'All', budget: 'All', group: '', category: '', member: '', sort: 'newest' },
                sortLabels: { newest: 'Newest', oldest: 'Oldest', amount_desc: 'Highest Amount', amount_asc: 'Lowest Amount' },

                init() {
                    setTimeout(() => { this.loading = false; }, 350);
                },

                isVisible(el) {
                    const name = el.dataset.name || '';
                    const tags = (el.dataset.tags || '').split('|');
                    const f = this.filters;

                    if (f.search && !name.includes(f.search.toLowerCase())) return false;
                    if (f.type !== 'All' && !tags.includes(f.type.toLowerCase())) return false;
                    if (f.budget !== 'All' && !tags.includes(f.budget.toLowerCase())) return false;
                    if (f.group && !tags.includes(f.group.toLowerCase())) return false;
                    if (f.category && !tags.includes(f.category.toLowerCase())) return false;

                    return true;
                },

                noResults() {
                    const grid = this.$el.querySelectorAll('[data-name]');
                    return grid.length > 0 && Array.from(grid).every(el => !this.isVisible(el));
                },
            }));

            Alpine.data('expenseForm', () => ({
                submitting: false,
                deleting: false,
                error: '',
                budgetsList: @json($budgetsList),
                groupsList: @json($groupsList),
                membersList: @json($membersList),
                form: {},

                init() {
                    this.form = this.blank();
                    this.$watch('$store.expenseModal.data', (val) => {
                        this.form = val ? this.hydrate(val) : this.blank();
                        this.deleting = false;
                        this.error = '';
                    });
                },

                blank() {
                    return {
                        name: '', amount: null, date: '', category: '', notes: '', description: '',
                        budgetMode: 'linked', budget: '', type: 'Personal', group: '',
                        paidBy: '', paymentMethod: 'Cash', receiptName: '',
                        split: { enabled: false, type: 'Equal', participants: [] },
                    };
                },

                hydrate(e) {
                    return {
                        name: e.name, amount: e.amount, date: e.date, category: e.category,
                        notes: e.notes || '', description: e.description || '',
                        budgetMode: e.budget ? 'linked' : 'none', budget: e.budget || '',
                        type: e.type, group: e.group || '',
                        paidBy: e.paid_by || '', paymentMethod: 'Cash', receiptName: '',
                        split: { enabled: !!e.split_type, type: e.split_type || 'Equal', participants: (e.members || []) },
                    };
                },

                get selectedBudget() {
                    return this.budgetsList.find(b => b.name === this.form.budget) || null;
                },

                get isGroupExpense() {
                    if (this.form.budgetMode === 'linked') return this.selectedBudget?.type === 'Group';
                    return this.form.type === 'Group';
                },

                get resolvedGroup() {
                    if (this.form.budgetMode === 'linked') return this.selectedBudget?.group || null;
                    return this.form.type === 'Group' ? this.form.group : null;
                },

                get participantOptions() {
                    return this.membersList;
                },

                toggleParticipant(member) {
                    const list = this.form.split.participants;
                    list.includes(member) ? this.form.split.participants = list.filter(m => m !== member) : list.push(member);
                },

                equalShare() {
                    const n = this.form.split.participants.length || 1;
                    return Math.round((this.form.amount || 0) / n);
                },

                submit(draft = false) {
                    if (!draft && (!this.form.name || !this.form.amount || !this.form.date || !this.form.category)) {
                        this.error = 'Please fill in all required fields.';
                        return;
                    }
                    this.error = '';
                    this.submitting = true;

                    setTimeout(() => {
                        this.submitting = false;
                        this.$store.expenseModal.open = false;
                    }, 600);
                },
            }));
        });
    </script>
</div>
