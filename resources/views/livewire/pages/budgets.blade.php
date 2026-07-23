<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new 
#[Layout('layouts.app')]
#[Title('Budgets')]
class extends Component {
    public function with(): array
    {
        $budgets = collect([
            (object)[
                'id' => 1, 'name' => 'Family Monthly Budget', 'type' => 'Group',
                'recurrence' => 'Recurring', 'frequency' => 'Monthly', 'status' => 'Active',
                'amount' => 500000, 'spent' => 350000,
                'start' => '2026-07-01', 'end' => '2026-07-31',
                'expenses_count' => 18, 'members_count' => 5,
                'contribution' => (object)[
                    'expected' => 120000, 'received' => 90000, 'outstanding' => 30000,
                    'members' => [
                        (object)['name' => 'Dad', 'status' => 'Paid', 'amount' => 20000],
                        (object)['name' => 'Mum', 'status' => 'Paid', 'amount' => 20000],
                        (object)['name' => 'Jane', 'status' => 'Paid', 'amount' => 20000],
                        (object)['name' => 'John', 'status' => 'Pending', 'amount' => 20000],
                        (object)['name' => 'Peter', 'status' => 'Pending', 'amount' => 20000],
                    ],
                ],
                'requests' => [
                    (object)['member' => 'Mother', 'item' => 'Food', 'amount' => 120000, 'status' => 'Approved', 'priority' => 'High'],
                    (object)['member' => 'Son', 'item' => 'School Books', 'amount' => 25000, 'status' => 'Pending', 'priority' => 'Medium'],
                    (object)['member' => 'Daughter', 'item' => 'Shoes', 'amount' => 15000, 'status' => 'Pending', 'priority' => 'Low'],
                ],
            ],
            (object)[
                'id' => 2, 'name' => 'Church Building Fund', 'type' => 'Group',
                'recurrence' => 'Recurring', 'frequency' => 'Monthly', 'status' => 'Active',
                'amount' => 150000, 'spent' => 120000,
                'start' => '2026-07-01', 'end' => '2026-07-31',
                'expenses_count' => 6, 'members_count' => 40,
                'contribution' => (object)[
                    'expected' => 150000, 'received' => 112500, 'outstanding' => 37500,
                    'members' => [
                        (object)['name' => 'Ifeoma', 'status' => 'Paid', 'amount' => 3750],
                        (object)['name' => 'Tunde', 'status' => 'Partial', 'amount' => 2000],
                        (object)['name' => 'Segun', 'status' => 'Overdue', 'amount' => 3750],
                    ],
                ],
                'requests' => [],
            ],
            (object)[
                'id' => 3, 'name' => 'Vacation Budget', 'type' => 'Personal',
                'recurrence' => 'One-time', 'frequency' => null, 'status' => 'Active',
                'amount' => 200000, 'spent' => 212000,
                'start' => '2026-06-01', 'end' => '2026-07-15',
                'expenses_count' => 9, 'members_count' => null,
                'contribution' => null, 'requests' => [],
            ],
            (object)[
                'id' => 4, 'name' => 'Transport', 'type' => 'Personal',
                'recurrence' => 'Recurring', 'frequency' => 'Weekly', 'status' => 'Active',
                'amount' => 25000, 'spent' => 18000,
                'start' => '2026-07-14', 'end' => '2026-07-21',
                'expenses_count' => 5, 'members_count' => null,
                'contribution' => null, 'requests' => [],
            ],
            (object)[
                'id' => 5, 'name' => 'Office Savings', 'type' => 'Group',
                'recurrence' => 'Recurring', 'frequency' => 'Monthly', 'status' => 'Active',
                'amount' => 80000, 'spent' => 30000,
                'start' => '2026-07-01', 'end' => '2026-07-31',
                'expenses_count' => 2, 'members_count' => 8,
                'contribution' => (object)[
                    'expected' => 80000, 'received' => 30000, 'outstanding' => 50000,
                    'members' => [
                        (object)['name' => 'Ada', 'status' => 'Paid', 'amount' => 10000],
                        (object)['name' => 'Bayo', 'status' => 'Pending', 'amount' => 10000],
                    ],
                ],
                'requests' => [],
            ],
        ]);

        $activeBudgets = $budgets->where('status', 'Active');
        $totalBudgetAmount = $budgets->sum('amount');
        $totalSpent = $budgets->sum('spent');
        $remainingBudget = $totalBudgetAmount - $totalSpent;
        $groupBudgets = $budgets->where('type', 'Group');
        $recurringBudgets = $budgets->where('recurrence', 'Recurring');
        $nearLimitBudgets = $budgets->filter(fn ($b) => $b->spent < $b->amount && ($b->spent / max($b->amount, 1)) >= 0.85);
        $overBudgets = $budgets->filter(fn ($b) => $b->spent > $b->amount);

        $summaryCards = [
            ['label' => 'Active Budgets', 'value' => $activeBudgets->count(), 'trend' => '+2 this month', 'up' => true, 'icon' => 'layers', 'tone' => 'blue'],
            ['label' => 'Total Budget Amount', 'value' => '₦' . number_format($totalBudgetAmount), 'trend' => '+6% from last month', 'up' => true, 'icon' => 'wallet', 'tone' => 'blue'],
            ['label' => 'Total Spent', 'value' => '₦' . number_format($totalSpent), 'trend' => '+9% from last month', 'up' => true, 'icon' => 'trend-up', 'tone' => 'blue'],
            ['label' => 'Remaining Budget', 'value' => '₦' . number_format($remainingBudget), 'trend' => '↓ 8% from last month', 'up' => false, 'icon' => 'piggy', 'tone' => 'blue'],
            ['label' => 'Group Budgets', 'value' => $groupBudgets->count(), 'trend' => 'Across ' . $groupBudgets->sum('members_count') . ' members', 'up' => null, 'icon' => 'users', 'tone' => 'blue'],
            ['label' => 'Recurring Budgets', 'value' => $recurringBudgets->count(), 'trend' => '3 renew next week', 'up' => null, 'icon' => 'repeat', 'tone' => 'blue'],
            ['label' => 'Budgets Near Limit', 'value' => $nearLimitBudgets->count(), 'trend' => '85%+ spent', 'up' => null, 'icon' => 'alert', 'tone' => 'orange'],
            ['label' => 'Over Budget', 'value' => $overBudgets->count(), 'trend' => 'Needs review', 'up' => null, 'icon' => 'alert-circle', 'tone' => 'red'],
        ];

        $attentionItems = collect([
            (object)['label' => 'Family Budget', 'meta' => '95% used', 'tone' => 'orange'],
            (object)['label' => 'Church Budget', 'meta' => '2 members have not contributed', 'tone' => 'blue'],
            (object)['label' => 'July Budget', 'meta' => 'Ends tomorrow', 'tone' => 'blue'],
            (object)['label' => 'Vacation Budget', 'meta' => 'Exceeded by ₦12,000', 'tone' => 'red'],
            (object)['label' => '4 expense requests', 'meta' => 'Awaiting approval', 'tone' => 'blue'],
        ]);

        $insights = [
            'Food represents 38% of your monthly budget.',
            'You have spent 12% less than last month.',
            'Three budgets will renew next week.',
            'One group budget is waiting for contributions.',
            'Four expense requests are pending approval.',
            'Transport spending has increased by 15%.',
        ];

        $timeline = collect([
            (object)['day' => 'Today', 'text' => 'Mary contributed ₦20,000'],
            (object)['day' => 'Yesterday', 'text' => 'Father approved School Fees request'],
            (object)['day' => 'Yesterday', 'text' => 'Added Electricity Expense'],
            (object)['day' => '3 days ago', 'text' => 'Created Family Monthly Budget'],
        ]);

        $toneClasses = [
            'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
            'orange' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
            'red' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600'],
            'green' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
        ];

        $statusBadge = [
            'Paid' => 'bg-emerald-50 text-emerald-700',
            'Partial' => 'bg-orange-50 text-orange-700',
            'Pending' => 'bg-slate-100 text-slate-600',
            'Overdue' => 'bg-rose-50 text-rose-700',
            'Approved' => 'bg-emerald-50 text-emerald-700',
            'Rejected' => 'bg-rose-50 text-rose-700',
            'Converted to Expense' => 'bg-blue-50 text-blue-700',
        ];

        $priorityBadge = [
            'High' => 'bg-rose-50 text-rose-700',
            'Medium' => 'bg-orange-50 text-orange-700',
            'Low' => 'bg-slate-100 text-slate-600',
        ];

        return compact(
            'budgets', 'activeBudgets', 'totalBudgetAmount', 'totalSpent', 'remainingBudget',
            'groupBudgets', 'recurringBudgets', 'nearLimitBudgets', 'overBudgets',
            'summaryCards', 'attentionItems', 'insights', 'timeline',
            'toneClasses', 'statusBadge', 'priorityBadge'
        );
    }
}; ?>

<div>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4" x-data="{}">
            <div>
                <h2 class="font-display text-xl font-semibold leading-tight text-slate-900">Budgets</h2>
                <p class="mt-0.5 text-sm text-slate-500">Plan, manage and monitor your personal and shared budgets.</p>
            </div>
            <button type="button" @click="$store.createBudget.open = true"
                    class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 transition hover:bg-blue-700">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
                Create Budget
            </button>
        </div>
    </x-slot>

    <style>
        [x-cloak] { display: none !important; }
        .b-card{
            background:#fff;
            border:1px solid #E5E9F0;
            border-radius:20px;
            box-shadow: 0 1px 2px rgba(15,23,42,0.03), 0 10px 28px -14px rgba(15,23,42,0.10);
        }
        .b-progress-track{ height:10px; border-radius:9999px; background:#EEF1F6; }
        .b-progress-fill{ height:10px; border-radius:9999px; transition: width .4s ease; }
        .badge{ display:inline-flex; align-items:center; border-radius:9999px; padding:.2rem .65rem; font-size:.7rem; font-weight:600; }
        .status-dot{ height:.5rem; width:.5rem; border-radius:9999px; display:inline-block; }
    </style>

    <div x-data="budgetsPage()" x-cloak class="mx-auto max-w-7xl space-y-8">

        @if ($budgets->isEmpty())
            {{-- Empty state --}}
            <div class="b-card flex flex-col items-center gap-4 px-6 py-20 text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="6" width="18" height="14" rx="2"/><path d="M3 10h18M8 6V4h8v2"/></svg>
                </div>
                <div>
                    <p class="font-display text-lg font-semibold text-slate-900">You haven't created any budgets yet.</p>
                    <p class="mt-1 text-sm text-slate-500">Create your first budget to start planning your finances.</p>
                </div>
                <button type="button" @click="$store.createBudget.open = true" class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700">Create Budget</button>
            </div>
        @else

            {{-- Summary cards --}}
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($summaryCards as $card)
                    @php $tone = $toneClasses[$card['tone']]; @endphp
                    <div class="b-card p-5">
                        <div class="flex items-start justify-between">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $tone['bg'] }} {{ $tone['text'] }}">
                                @switch($card['icon'])
                                    @case('layers')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 13 9 5 9-5"/></svg>
                                        @break
                                    @case('wallet')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7a2 2 0 0 1 2-2h13a1 1 0 0 1 1 1v3"/><path d="M3 7v11a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-5a2 2 0 1 0 0 4"/></svg>
                                        @break
                                    @case('trend-up')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 17 9 11l4 4 8-8M21 7h-6v6"/></svg>
                                        @break
                                    @case('piggy')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 5c-4 0-7 2.5-7 6 0 1.6.6 2.7 1.5 3.6L5 17h3l.8-1a8 8 0 0 0 2.2.3c4 0 7-2.6 7-6.3S15 5 11 5Z"/><circle cx="15" cy="10" r=".8" fill="currentColor" stroke="none"/></svg>
                                        @break
                                    @case('users')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.5"/><path d="M3.5 19c.7-3 3-5 5.5-5s4.8 2 5.5 5M14.5 19c.3-2 1.6-3.6 3.3-4.3"/></svg>
                                        @break
                                    @case('repeat')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 2l4 4-4 4M3 11V9a4 4 0 0 1 4-4h14M7 22l-4-4 4-4M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                                        @break
                                    @case('alert')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10.3 3.9 1.8 18a1.5 1.5 0 0 0 1.3 2.2h17.8a1.5 1.5 0 0 0 1.3-2.2L13.7 3.9a1.5 1.5 0 0 0-2.6 0Z"/><path d="M12 9v4M12 17h.01"/></svg>
                                        @break
                                    @default
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>
                                @endswitch
                            </span>
                        </div>
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

            {{-- Search & filters --}}
            <div class="b-card p-4 sm:p-5">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="relative w-full lg:max-w-sm">
                        <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" x-model="search" placeholder="Search budgets..."
                               class="w-full rounded-full border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-4 text-sm text-slate-700 placeholder-slate-400 focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <template x-for="chip in filterChips" :key="chip">
                            <button type="button" @click="toggleFilter(chip)"
                                    :class="activeFilters.includes(chip) ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'"
                                    class="rounded-full border px-3.5 py-1.5 text-xs font-semibold transition" x-text="chip"></button>
                        </template>

                        <div class="relative" x-data="{ sortOpen: false }">
                            <button type="button" @click="sortOpen = !sortOpen" @click.away="sortOpen = false"
                                    class="flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M6 12h12M10 18h4"/></svg>
                                <span x-text="sortLabels[sortBy]"></span>
                            </button>
                            <div x-show="sortOpen" x-transition class="absolute right-0 z-20 mt-2 w-44 rounded-xl border border-slate-100 bg-white p-1.5 shadow-lg">
                                <template x-for="(label, key) in sortLabels" :key="key">
                                    <button type="button" @click="sortBy = key; sortOpen = false; applySort()"
                                            class="block w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-slate-600 hover:bg-slate-50"
                                            :class="sortBy === key ? 'text-blue-600 bg-blue-50' : ''" x-text="label"></button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Budget cards + side panels --}}
            <div class="grid gap-6 xl:grid-cols-[1fr_320px]">

                {{-- Budget cards --}}
                <div x-ref="budgetGrid" class="space-y-5">
                    @foreach ($budgets as $budget)
                        @php
                            $pct = $budget->amount > 0 ? round(($budget->spent / $budget->amount) * 100) : 0;
                            $pctCapped = min(100, $pct);
                            $barColor = $pct > 100 ? '#EF4444' : ($pct >= 85 ? '#F97316' : '#2563EB');
                            $filterTags = collect([$budget->type, $budget->status, $budget->recurrence, $budget->frequency])->filter()->implode('|');
                        @endphp
                        <div class="b-card p-5 sm:p-6"
                             data-name="{{ strtolower($budget->name) }}"
                             data-tags="{{ strtolower($filterTags) }}"
                             data-amount="{{ $budget->amount }}"
                             data-progress="{{ $pct }}"
                             data-start="{{ $budget->start }}"
                             x-show="isVisible($el)">

                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-display text-base font-semibold text-slate-900">{{ $budget->name }}</h3>
                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        <span class="badge {{ $budget->type === 'Group' ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-600' }}">{{ $budget->type }}</span>
                                        <span class="badge bg-slate-100 text-slate-600">{{ $budget->recurrence }}</span>
                                        @if ($budget->frequency)
                                            <span class="badge bg-slate-100 text-slate-600">{{ $budget->frequency }}</span>
                                        @endif
                                        <span class="badge bg-emerald-50 text-emerald-700">{{ $budget->status }}</span>
                                    </div>
                                </div>

                                {{-- Card actions menu --}}
                                <div class="relative" x-data="{ menuOpen: false }">
                                    <button type="button" @click="menuOpen = !menuOpen" @click.away="menuOpen = false"
                                            class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/></svg>
                                    </button>
                                    <div x-show="menuOpen" x-transition class="absolute right-0 z-20 mt-1 w-40 rounded-xl border border-slate-100 bg-white p-1.5 shadow-lg text-sm">
                                        <a href="#" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50">View</a>
                                        <a href="#" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50">Add Expense</a>
                                        <a href="#" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50">Edit</a>
                                        <a href="#" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50">Duplicate</a>
                                        <a href="#" class="block rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50">Archive</a>
                                        <a href="#" class="block rounded-lg px-3 py-2 text-rose-600 hover:bg-rose-50">Delete</a>
                                    </div>
                                </div>
                            </div>

                            {{-- Progress --}}
                            <div class="mt-5 grid grid-cols-3 gap-3 text-sm sm:flex sm:items-center sm:justify-between sm:gap-6">
                                <div><p class="text-xs text-slate-400">Budget</p><p class="font-mono font-semibold text-slate-900">₦{{ number_format($budget->amount) }}</p></div>
                                <div><p class="text-xs text-slate-400">Spent</p><p class="font-mono font-semibold text-slate-900">₦{{ number_format($budget->spent) }}</p></div>
                                <div><p class="text-xs text-slate-400">Remaining</p><p class="font-mono font-semibold {{ $budget->amount - $budget->spent < 0 ? 'text-rose-600' : 'text-slate-900' }}">₦{{ number_format($budget->amount - $budget->spent) }}</p></div>
                            </div>
                            <div class="mt-3">
                                <div class="b-progress-track">
                                    <div class="b-progress-fill" style="width:{{ $pctCapped }}%; background:{{ $barColor }};"></div>
                                </div>
                                <p class="mt-1.5 text-xs font-medium" style="color:{{ $barColor }}">{{ $pct }}% used</p>
                            </div>

                            {{-- Meta row --}}
                            <div class="mt-4 flex flex-wrap gap-x-6 gap-y-1 text-xs text-slate-500">
                                <span>{{ \Carbon\Carbon::parse($budget->start)->format('M j') }} – {{ \Carbon\Carbon::parse($budget->end)->format('M j, Y') }}</span>
                                <span>{{ $budget->expenses_count }} expenses</span>
                                @if ($budget->members_count)
                                    <span>{{ $budget->members_count }} members</span>
                                @endif
                            </div>

                            @if ($budget->contribution || !empty($budget->requests))
                                <div class="mt-4 border-t border-slate-100 pt-4" x-data="{ expanded: false }">
                                    <button type="button" @click="expanded = !expanded" class="flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-700">
                                        <span x-text="expanded ? 'Hide details' : 'Show contribution & request details'"></span>
                                        <svg :class="expanded ? 'rotate-180' : ''" class="transition-transform" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                                    </button>

                                    <div x-show="expanded" x-transition.duration.200ms class="mt-4 space-y-5">

                                        {{-- Group Contribution Panel --}}
                                        @if ($budget->contribution)
                                            @php $c = $budget->contribution; $cPct = $c->expected > 0 ? min(100, round(($c->received / $c->expected) * 100)) : 0; @endphp
                                            <div class="rounded-2xl bg-slate-50 p-4">
                                                <p class="text-sm font-semibold text-slate-800">Contribution Status</p>
                                                <div class="mt-3 grid grid-cols-3 gap-3 text-sm">
                                                    <div><p class="text-xs text-slate-400">Expected</p><p class="font-mono font-semibold text-slate-900">₦{{ number_format($c->expected) }}</p></div>
                                                    <div><p class="text-xs text-slate-400">Received</p><p class="font-mono font-semibold text-emerald-600">₦{{ number_format($c->received) }}</p></div>
                                                    <div><p class="text-xs text-slate-400">Outstanding</p><p class="font-mono font-semibold text-rose-600">₦{{ number_format($c->outstanding) }}</p></div>
                                                </div>
                                                <div class="b-progress-track mt-3"><div class="b-progress-fill" style="width:{{ $cPct }}%; background:#2563EB;"></div></div>

                                                <div class="mt-4 divide-y divide-slate-200">
                                                    @foreach ($c->members as $m)
                                                        <div class="flex items-center justify-between py-2 text-sm">
                                                            <span class="flex items-center gap-2 text-slate-700">
                                                                <span class="status-dot {{ $m->status === 'Paid' ? 'bg-emerald-500' : ($m->status === 'Overdue' ? 'bg-rose-500' : ($m->status === 'Partial' ? 'bg-orange-500' : 'bg-slate-400')) }}"></span>
                                                                {{ $m->name }}
                                                            </span>
                                                            <span class="flex items-center gap-3">
                                                                <span class="badge {{ $statusBadge[$m->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $m->status }}</span>
                                                                <span class="font-mono w-20 text-right text-xs text-slate-500">₦{{ number_format($m->amount) }}</span>
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div class="mt-4 flex flex-wrap gap-2">
                                                    <button class="rounded-full bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">Record Contribution</button>
                                                    <button class="rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:border-slate-300">View Contribution History</button>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Budget Planning / Expense Requests --}}
                                        @if ($budget->type === 'Group' && !empty($budget->requests))
                                            @php
                                                $reqCollection = collect($budget->requests);
                                                $reqTotal = $reqCollection->sum('amount');
                                                $reqApproved = $reqCollection->where('status', 'Approved')->sum('amount');
                                                $reqPending = $reqCollection->where('status', 'Pending')->sum('amount');
                                            @endphp
                                            <div class="rounded-2xl bg-slate-50 p-4">
                                                <p class="text-sm font-semibold text-slate-800">Budget Requests</p>
                                                <div class="mt-3 grid grid-cols-3 gap-3 text-sm">
                                                    <div><p class="text-xs text-slate-400">Total Requested</p><p class="font-mono font-semibold text-slate-900">₦{{ number_format($reqTotal) }}</p></div>
                                                    <div><p class="text-xs text-slate-400">Approved</p><p class="font-mono font-semibold text-emerald-600">₦{{ number_format($reqApproved) }}</p></div>
                                                    <div><p class="text-xs text-slate-400">Pending</p><p class="font-mono font-semibold text-orange-600">₦{{ number_format($reqPending) }}</p></div>
                                                </div>

                                                <div class="mt-4 divide-y divide-slate-200">
                                                    @foreach ($reqCollection as $r)
                                                        <div class="flex items-center justify-between gap-3 py-2.5 text-sm">
                                                            <div>
                                                                <p class="font-medium text-slate-800">{{ $r->item }}</p>
                                                                <p class="text-xs text-slate-400">{{ $r->member }} · <span class="badge {{ $priorityBadge[$r->priority] ?? '' }}">{{ $r->priority }}</span></p>
                                                            </div>
                                                            <div class="flex items-center gap-3">
                                                                <span class="font-mono text-xs text-slate-600">₦{{ number_format($r->amount) }}</span>
                                                                <span class="badge {{ $statusBadge[$r->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $r->status }}</span>
                                                                @if ($r->status === 'Pending')
                                                                    <button class="text-xs font-semibold text-emerald-600 hover:underline">Approve</button>
                                                                    <button class="text-xs font-semibold text-rose-600 hover:underline">Reject</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <button class="mt-4 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100">Submit Budget Request</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <p x-show="noResults()" x-cloak class="b-card p-8 text-center text-sm text-slate-500">No budgets match your search or filters.</p>
                </div>

                {{-- Side panels --}}
                <div class="space-y-6">
                    <div class="b-card p-5">
                        <h3 class="font-display text-sm font-semibold text-slate-900">Needs Attention</h3>
                        <div class="mt-3 space-y-1">
                            @foreach ($attentionItems as $item)
                                @php $tone = $toneClasses[$item->tone] ?? $toneClasses['blue']; @endphp
                                <div class="flex items-start gap-2.5 rounded-lg px-2 py-2 hover:bg-slate-50">
                                    <span class="mt-0.5 status-dot {{ $item->tone === 'red' ? 'bg-rose-500' : ($item->tone === 'orange' ? 'bg-orange-500' : 'bg-blue-500') }}"></span>
                                    <div class="text-sm">
                                        <p class="font-medium text-slate-800">{{ $item->label }}</p>
                                        <p class="text-xs text-slate-500">{{ $item->meta }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="b-card p-5">
                        <h3 class="font-display text-sm font-semibold text-slate-900">Budget Insights</h3>
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
                        <h3 class="font-display text-sm font-semibold text-slate-900">Budget Timeline</h3>
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

        {{-- Create Budget modal --}}
        <template x-teleport="body">
            <div x-show="$store.createBudget.open" x-cloak
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4"
                 @keydown.escape.window="$store.createBudget.open = false">

                <div x-show="$store.createBudget.open" x-transition.opacity
                     class="absolute inset-0 bg-slate-900/40" @click="$store.createBudget.open = false"></div>

                <div x-show="$store.createBudget.open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     x-data="budgetForm()"
                     class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-7">

                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-display text-lg font-semibold text-slate-900">Create Budget</h3>
                            <p class="mt-0.5 text-sm text-slate-500">Set up a personal or shared budget.</p>
                        </div>
                        <button type="button" @click="$store.createBudget.open = false" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form class="mt-6 space-y-5" @submit.prevent="submit()">

                        <div>
                            <label class="text-xs font-semibold text-slate-600">Budget name</label>
                            <input type="text" x-model="form.name" required placeholder="e.g. Family Monthly Budget"
                                   class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold text-slate-600">Type</label>
                                <div class="mt-1.5 grid grid-cols-2 gap-2">
                                    <button type="button" @click="form.type = 'Personal'"
                                            :class="form.type === 'Personal' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-500'"
                                            class="rounded-xl border px-3 py-2 text-xs font-semibold">Personal</button>
                                    <button type="button" @click="form.type = 'Group'"
                                            :class="form.type === 'Group' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-500'"
                                            class="rounded-xl border px-3 py-2 text-xs font-semibold">Group</button>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-600">Recurrence</label>
                                <div class="mt-1.5 grid grid-cols-2 gap-2">
                                    <button type="button" @click="form.recurrence = 'One-time'"
                                            :class="form.recurrence === 'One-time' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-500'"
                                            class="rounded-xl border px-3 py-2 text-xs font-semibold">One-time</button>
                                    <button type="button" @click="form.recurrence = 'Recurring'"
                                            :class="form.recurrence === 'Recurring' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-500'"
                                            class="rounded-xl border px-3 py-2 text-xs font-semibold">Recurring</button>
                                </div>
                            </div>
                        </div>

                        <div x-show="form.recurrence === 'Recurring'" x-transition>
                            <label class="text-xs font-semibold text-slate-600">Frequency</label>
                            <select x-model="form.frequency" class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                <option value="Weekly">Weekly</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Yearly">Yearly</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-600">Budget amount (₦)</label>
                            <input type="number" min="0" x-model.number="form.amount" required placeholder="0.00"
                                   class="font-mono mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold text-slate-600">Start date</label>
                                <input type="date" x-model="form.start" required
                                       class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-600">End date</label>
                                <input type="date" x-model="form.end" required
                                       class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            </div>
                        </div>

                        <div x-show="form.type === 'Group'" x-transition>
                            <label class="text-xs font-semibold text-slate-600">Invite members</label>
                            <input type="text" x-model="form.members" placeholder="Comma-separated emails or names"
                                   class="mt-1.5 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <p class="mt-1 text-xs text-slate-400">You'll be able to set roles and permissions after creating the budget.</p>
                        </div>

                        <p x-show="error" x-text="error" class="text-sm font-medium text-rose-600"></p>

                        <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                            <button type="button" @click="$store.createBudget.open = false" class="rounded-full px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                            <button type="submit" :disabled="submitting"
                                    class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700 disabled:opacity-60">
                                <span x-show="!submitting">Create Budget</span>
                                <span x-show="submitting">Creating…</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('createBudget', { open: false });

            Alpine.data('budgetForm', () => ({
                submitting: false,
                error: '',
                form: {
                    name: '',
                    type: 'Personal',
                    recurrence: 'One-time',
                    frequency: 'Monthly',
                    amount: null,
                    start: '',
                    end: '',
                    members: '',
                },

                submit() {
                    if (!this.form.name || !this.form.amount || !this.form.start || !this.form.end) {
                        this.error = 'Please fill in all required fields.';
                        return;
                    }
                    this.error = '';
                    this.submitting = true;

                    setTimeout(() => {
                        this.submitting = false;
                        this.$store.createBudget.open = false;
                        this.form = { name: '', type: 'Personal', recurrence: 'One-time', frequency: 'Monthly', amount: null, start: '', end: '', members: '' };
                    }, 600);
                },
            }));

            Alpine.data('budgetsPage', () => ({
                search: '',
                activeFilters: [],
                sortBy: 'newest',
                filterChips: ['Personal', 'Group', 'Active', 'Completed', 'Archived', 'One-time', 'Recurring', 'Monthly', 'Weekly', 'Yearly'],
                sortLabels: {
                    newest: 'Newest',
                    oldest: 'Oldest',
                    amount_desc: 'Highest Amount',
                    amount_asc: 'Lowest Amount',
                    progress_desc: 'Most Progress',
                    progress_asc: 'Least Progress',
                },

                toggleFilter(chip) {
                    const key = chip.toLowerCase();
                    this.activeFilters.includes(key)
                        ? this.activeFilters = this.activeFilters.filter(f => f !== key)
                        : this.activeFilters.push(key);
                },

                isVisible(el) {
                    const name = el.dataset.name || '';
                    const tags = (el.dataset.tags || '').split('|');

                    if (this.search && !name.includes(this.search.toLowerCase())) return false;
                    if (this.activeFilters.length && !this.activeFilters.every(f => tags.includes(f))) return false;

                    return true;
                },

                noResults() {
                    if (!this.$refs.budgetGrid) return false;
                    return Array.from(this.$refs.budgetGrid.children)
                        .filter(el => el.dataset && el.dataset.name)
                        .every(el => !this.isVisible(el));
                },

                applySort() {
                    const container = this.$refs.budgetGrid;
                    if (!container) return;
                    const cards = Array.from(container.children).filter(el => el.dataset && el.dataset.name);

                    cards.sort((a, b) => {
                        switch (this.sortBy) {
                            case 'oldest': return new Date(a.dataset.start) - new Date(b.dataset.start);
                            case 'amount_desc': return b.dataset.amount - a.dataset.amount;
                            case 'amount_asc': return a.dataset.amount - b.dataset.amount;
                            case 'progress_desc': return b.dataset.progress - a.dataset.progress;
                            case 'progress_asc': return a.dataset.progress - b.dataset.progress;
                            default: return new Date(b.dataset.start) - new Date(a.dataset.start);
                        }
                    });

                    cards.forEach(c => container.appendChild(c));
                },
            }));
        });
    </script>
</div>
