<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new 
#[Layout('layouts.app')]
#[Title('Savings')]
class extends Component {
    public function with(): array
    {
        $plans = collect([
            (object)[
                'id' => 1, 'name' => 'Emergency Fund', 'description' => 'For unexpected expenses',
                'target' => 500000, 'current' => 320000, 'has_duration' => true,
                'start_date' => '2026-01-01', 'end_date' => '2026-12-31', 'status' => 'Active',
                'records' => [
                    ['id' => 1, 'amount' => 25000, 'date' => '2026-07-12', 'description' => 'Salary Savings', 'method' => 'Bank Transfer', 'created_by' => 'Emmanuel'],
                    ['id' => 2, 'amount' => 20000, 'date' => '2026-06-28', 'description' => 'Side hustle income', 'method' => 'Cash', 'created_by' => 'Emmanuel'],
                    ['id' => 3, 'amount' => 40000, 'date' => '2026-06-01', 'description' => '', 'method' => 'Bank Transfer', 'created_by' => 'Emmanuel'],
                ],
                'activity' => [
                    ['label' => 'Added ₦25,000', 'date' => 'Today'],
                    ['label' => 'Edited savings plan', 'date' => 'Yesterday'],
                    ['label' => 'Created savings plan', 'date' => 'Jan 1'],
                ],
                'trend' => [180000, 210000, 240000, 260000, 280000, 320000],
                'monthly' => [30000, 30000, 20000, 20000, 20000, 40000],
            ],
            (object)[
                'id' => 2, 'name' => 'Vacation Fund', 'description' => 'Trip to Zanzibar next year',
                'target' => 250000, 'current' => 60000, 'has_duration' => true,
                'start_date' => '2026-03-01', 'end_date' => '2027-03-01', 'status' => 'Active',
                'records' => [['id' => 4, 'amount' => 60000, 'date' => '2026-06-01', 'description' => 'Initial deposit', 'method' => 'Bank Transfer', 'created_by' => 'Emmanuel']],
                'activity' => [['label' => 'Added ₦60,000', 'date' => 'Jun 1'], ['label' => 'Created savings plan', 'date' => 'Mar 1']],
                'trend' => [0, 0, 0, 60000, 60000, 60000],
                'monthly' => [0, 0, 0, 60000, 0, 0],
            ],
            (object)[
                'id' => 3, 'name' => 'Car Fund', 'description' => 'Saving toward a used Corolla',
                'target' => 2000000, 'current' => 1800000, 'has_duration' => false,
                'start_date' => '2025-09-01', 'end_date' => null, 'status' => 'Active',
                'records' => [
                    ['id' => 5, 'amount' => 400000, 'date' => '2026-07-01', 'description' => 'Bonus payment', 'method' => 'Bank Transfer', 'created_by' => 'Emmanuel'],
                    ['id' => 6, 'amount' => 1400000, 'date' => '2026-01-01', 'description' => 'Rollover from savings', 'method' => 'Bank Transfer', 'created_by' => 'Emmanuel'],
                ],
                'activity' => [['label' => 'Added ₦400,000', 'date' => 'Jul 1'], ['label' => 'Created savings plan', 'date' => 'Sep 1, 2025']],
                'trend' => [1400000, 1400000, 1400000, 1400000, 1400000, 1800000],
                'monthly' => [0, 0, 0, 0, 0, 400000],
            ],
            (object)[
                'id' => 4, 'name' => 'New Laptop', 'description' => 'Work laptop replacement',
                'target' => 800000, 'current' => 800000, 'has_duration' => true,
                'start_date' => '2026-02-01', 'end_date' => '2026-06-01', 'status' => 'Completed',
                'records' => [
                    ['id' => 7, 'amount' => 400000, 'date' => '2026-05-01', 'description' => '', 'method' => 'Bank Transfer', 'created_by' => 'Emmanuel'],
                    ['id' => 8, 'amount' => 400000, 'date' => '2026-02-15', 'description' => '', 'method' => 'Cash', 'created_by' => 'Emmanuel'],
                ],
                'activity' => [['label' => 'Target reached', 'date' => 'May 1'], ['label' => 'Created savings plan', 'date' => 'Feb 1']],
                'trend' => [400000, 400000, 400000, 800000, 800000, 800000],
                'monthly' => [400000, 0, 0, 400000, 0, 0],
            ],
        ]);

        $totalSavings = $plans->sum('current');
        $activePlans = $plans->where('status', 'Active')->count();
        $completedPlans = $plans->where('status', 'Completed')->count();
        $allRecords = $plans->flatMap(fn ($p) => $p->records);
        $thisMonthSavings = collect($allRecords)->filter(fn ($r) => \Carbon\Carbon::parse($r['date'])->isSameMonth(now()))->sum('amount');
        $totalDeposits = collect($allRecords)->count();
        $avgMonthlySavings = $totalDeposits > 0 ? round($totalSavings / max(1, $plans->count())) : 0;

        $summaryCards = [
            ['label' => 'Total Savings', 'value' => '₦' . number_format($totalSavings), 'trend' => '↑ 12% vs last month', 'up' => true, 'icon' => 'piggy'],
            ['label' => 'Active Savings Plans', 'value' => $activePlans, 'trend' => 'In progress', 'up' => null, 'icon' => 'layers'],
            ['label' => 'Completed Plans', 'value' => $completedPlans, 'trend' => 'Targets reached', 'up' => true, 'icon' => 'check'],
            ['label' => "This Month's Savings", 'value' => '₦' . number_format($thisMonthSavings), 'trend' => '↑ 8% vs last month', 'up' => true, 'icon' => 'trend-up'],
            ['label' => 'Total Deposits', 'value' => $totalDeposits, 'trend' => '+3 this month', 'up' => true, 'icon' => 'list'],
            ['label' => 'Average Monthly Savings', 'value' => '₦' . number_format($avgMonthlySavings), 'trend' => 'Per plan', 'up' => null, 'icon' => 'gauge'],
        ];

        $attentionItems = collect([
            (object)['label' => 'Vacation Fund', 'meta' => 'No deposit for 45 days', 'tone' => 'red'],
            (object)['label' => 'Emergency Fund', 'meta' => 'Target date approaching', 'tone' => 'amber'],
            (object)['label' => 'Car Fund', 'meta' => '90% complete', 'tone' => 'blue'],
        ]);

        $insights = [
            'You have saved ₦80,000 this month.',
            'Average monthly savings increased by 12%.',
            'Emergency Fund is 64% complete.',
            'Vacation Fund has no recent deposits.',
            'Two savings plans have reached their targets.',
        ];

        $toneDot = ['blue' => 'bg-blue-500', 'amber' => 'bg-amber-500', 'red' => 'bg-rose-500', 'green' => 'bg-emerald-500'];
        $statusBadge = ['Active' => 'bg-blue-50 text-blue-700', 'Completed' => 'bg-emerald-50 text-emerald-700', 'Archived' => 'bg-slate-100 text-slate-500'];

        return compact(
            'plans', 'totalSavings', 'activePlans', 'completedPlans', 'allRecords',
            'thisMonthSavings', 'totalDeposits', 'avgMonthlySavings', 'summaryCards',
            'attentionItems', 'insights', 'toneDot', 'statusBadge'
        );
    }
}; ?>

<div>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4" x-data="{}">
            <div>
                <h2 class="font-display text-xl font-semibold leading-tight text-slate-900">Savings</h2>
                <p class="mt-0.5 text-sm text-slate-500">Track your savings goals and monitor your progress.</p>
            </div>
            <div class="hidden items-center gap-2 sm:flex">
                <button type="button" @click="$store.recordModal.data = null; $store.recordModal.open = true"
                        class="rounded-full border border-blue-200 bg-blue-50 px-5 py-2.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">
                    + Record Savings
                </button>
                <button type="button" @click="$store.planModal.mode = 'create'; $store.planModal.data = null; $store.planModal.open = true"
                        class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 transition hover:bg-blue-700">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
                    Create Savings Plan
                </button>
            </div>
        </div>
    </x-slot>

    <style>
        [x-cloak] { display: none !important; }
        .b-card{ background:#fff; border:1px solid #E5E9F0; border-radius:20px; box-shadow: 0 1px 2px rgba(15,23,42,0.03), 0 10px 28px -14px rgba(15,23,42,0.10); }
        .b-progress-track{ height:8px; border-radius:9999px; background:#EEF1F6; }
        .b-progress-fill{ height:8px; border-radius:9999px; transition: width .4s ease; }
        .badge{ display:inline-flex; align-items:center; border-radius:9999px; padding:.2rem .6rem; font-size:.7rem; font-weight:600; }
        .status-dot{ height:.5rem; width:.5rem; border-radius:9999px; display:inline-block; }
        .field-label{ font-size:.75rem; font-weight:600; color:#475569; }
        .field-input{ margin-top:.375rem; width:100%; border-radius:.75rem; border:1px solid #E2E8F0; padding:.625rem .875rem; font-size:.875rem; }
        .field-input:focus{ outline:none; border-color:#60A5FA; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .segbtn{ border-radius:.75rem; border:1px solid #E2E8F0; padding:.5rem .75rem; font-size:.75rem; font-weight:600; color:#64748B; }
        .segbtn.active{ border-color:#2563EB; background:#EFF6FF; color:#1D4ED8; }
        .skeleton{ background: linear-gradient(90deg,#EEF1F6 25%,#F6F8FA 37%,#EEF1F6 63%); background-size:400% 100%; animation: shimmer 1.4s ease infinite; border-radius:16px; }
        @keyframes shimmer{ 0%{ background-position:100% 50%;} 100%{ background-position:0 50%;} }
    </style>

    <div x-data="savingsPage()" x-init="init()" x-cloak class="relative mx-auto max-w-7xl space-y-8 pb-24">

        {{-- Mobile bottom action bar --}}
        <div class="fixed inset-x-0 bottom-0 z-40 flex gap-2 border-t border-slate-200 bg-white/95 p-3 backdrop-blur-md sm:hidden">
            <button type="button" @click="$store.recordModal.data = null; $store.recordModal.open = true"
                    class="flex-1 rounded-full border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700">+ Record Savings</button>
            <button type="button" @click="$store.planModal.mode = 'create'; $store.planModal.data = null; $store.planModal.open = true"
                    class="flex-1 rounded-full bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200">+ Create Plan</button>
        </div>

        @if ($plans->isEmpty())
            <div class="b-card flex flex-col items-center gap-4 px-6 py-20 text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 5c-4 0-7 2.5-7 6 0 1.6.6 2.7 1.5 3.6L5 17h3l.8-1a8 8 0 0 0 2.2.3c4 0 7-2.6 7-6.3S15 5 11 5Z"/><circle cx="15" cy="10" r=".8" fill="currentColor" stroke="none"/></svg>
                </div>
                <div>
                    <p class="font-display text-lg font-semibold text-slate-900">No Savings Plans Yet</p>
                    <p class="mt-1 text-sm text-slate-500">Create your first savings plan.</p>
                </div>
                <button type="button" @click="$store.planModal.mode = 'create'; $store.planModal.data = null; $store.planModal.open = true"
                        class="rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700">Create Savings Plan</button>
            </div>
        @else

            {{-- Summary cards --}}
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($summaryCards as $card)
                    <div class="b-card p-5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            @switch($card['icon'])
                                @case('piggy')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 5c-4 0-7 2.5-7 6 0 1.6.6 2.7 1.5 3.6L5 17h3l.8-1a8 8 0 0 0 2.2.3c4 0 7-2.6 7-6.3S15 5 11 5Z"/><circle cx="15" cy="10" r=".8" fill="currentColor" stroke="none"/></svg>
                                    @break
                                @case('layers')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 13 9 5 9-5"/></svg>
                                    @break
                                @case('check')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m5 13 4 4L19 7"/></svg>
                                    @break
                                @case('trend-up')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 17 9 11l4 4 8-8M21 7h-6v6"/></svg>
                                    @break
                                @case('list')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                    @break
                                @case('gauge')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 14a8 8 0 1 1 16 0"/><path d="M12 14l4-4"/></svg>
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
                        <input type="text" x-model="filters.search" placeholder="Search plan name or description..."
                               class="w-full rounded-full border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-4 text-sm focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    <select x-model="filters.duration" class="hidden rounded-full border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-600 sm:block">
                        <option>Today</option><option>This Week</option><option selected>This Month</option>
                        <option>Last Month</option><option>Last 3 Months</option><option>This Year</option><option>Custom Date Range</option>
                    </select>
                    <button type="button" @click="mobileFiltersOpen = true" class="flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-600 sm:hidden">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
                        Filters
                    </button>
                </div>

                <div class="mt-3 hidden flex-wrap items-center gap-2 sm:flex">
                    <template x-for="opt in ['All','Active','Completed','Archived']" :key="opt">
                        <button type="button" @click="filters.status = opt" :class="filters.status === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                    </template>

                    <select x-model="filters.plan" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">
                        <option value="">All Plans</option>
                        @foreach ($plans as $p) <option value="{{ strtolower($p->name) }}">{{ $p->name }}</option> @endforeach
                    </select>

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
                                <label class="field-label">Duration</label>
                                <select x-model="filters.duration" class="field-input">
                                    <option>Today</option><option>This Week</option><option selected>This Month</option>
                                    <option>Last Month</option><option>Last 3 Months</option><option>This Year</option><option>Custom Date Range</option>
                                </select>
                            </div>
                            <div>
                                <label class="field-label">Status</label>
                                <div class="mt-1.5 grid grid-cols-3 gap-2">
                                    <template x-for="opt in ['All','Active','Completed','Archived']" :key="opt">
                                        <button type="button" @click="filters.status = opt" :class="filters.status === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <label class="field-label">Plan</label>
                                <select x-model="filters.plan" class="field-input">
                                    <option value="">All Plans</option>
                                    @foreach ($plans as $p) <option value="{{ strtolower($p->name) }}">{{ $p->name }}</option> @endforeach
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

            {{-- Plan cards + side column --}}
            <div class="grid gap-6 xl:grid-cols-[1fr_320px]">
                <div x-show="loading" class="grid gap-5 sm:grid-cols-2">
                    <div class="skeleton h-52"></div><div class="skeleton h-52"></div>
                </div>

                <div x-show="!loading" class="grid gap-5 sm:grid-cols-2">
                    @foreach ($plans as $plan)
                        @php
                            $pct = $plan->target > 0 ? min(100, round(($plan->current / $plan->target) * 100)) : 0;
                            $tags = collect([$plan->status, strtolower($plan->name), $plan->has_duration ? 'has-duration' : 'no-duration'])->implode('|');
                        @endphp
                        <div class="b-card p-5" data-name="{{ strtolower($plan->name . ' ' . $plan->description) }}" data-status="{{ strtolower($plan->status) }}"
                             x-show="isVisible($el)" x-data="{ confirmDelete: false }">

                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-display text-base font-semibold text-slate-900">{{ $plan->name }}</h3>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $plan->description }}</p>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <span class="badge {{ $statusBadge[$plan->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $plan->status }}</span>
                                    @unless ($plan->has_duration)
                                        <span class="badge bg-slate-100 text-slate-500">No Duration</span>
                                    @endunless
                                </div>
                            </div>

                            <div class="mt-4 flex items-end justify-between">
                                <div><p class="text-xs text-slate-400">Current</p><p class="font-mono text-lg font-semibold text-slate-900">₦{{ number_format($plan->current) }}</p></div>
                                @if ($plan->target)
                                    <div class="text-right"><p class="text-xs text-slate-400">Target</p><p class="font-mono text-sm font-semibold text-slate-600">₦{{ number_format($plan->target) }}</p></div>
                                @endif
                            </div>

                            @if ($plan->target)
                                <div class="mt-2">
                                    <div class="b-progress-track"><div class="b-progress-fill" style="width:{{ $pct }}%; background:{{ $pct >= 100 ? '#10B981' : '#2563EB' }};"></div></div>
                                    <p class="mt-1 text-xs font-medium text-blue-600">{{ $pct }}%</p>
                                </div>
                            @endif

                            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500">
                                <span>Started {{ \Carbon\Carbon::parse($plan->start_date)->format('M Y') }}</span>
                                @if ($plan->end_date)<span>Ends {{ \Carbon\Carbon::parse($plan->end_date)->format('d M Y') }}</span>@endif
                                <span>{{ count($plan->records) }} deposits</span>
                            </div>

                            <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3" x-show="!confirmDelete">
                                <button type="button" @click="$store.planDrawer.plan=@js($plan); $store.planDrawer.tab='overview'; $store.planDrawer.open=true"
                                        class="rounded-full border border-slate-200 px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300">View</button>
                                <button type="button" @click="$store.planModal.mode='edit'; $store.planModal.data=@js($plan); $store.planModal.open=true"
                                        class="rounded-full border border-slate-200 px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300">Edit</button>
                                <button type="button" @click="confirmDelete = true" class="rounded-full border border-rose-200 px-3.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                            </div>
                            <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3 text-sm" x-show="confirmDelete" x-cloak>
                                <span class="text-slate-600">Delete this plan?</span>
                                <button type="button" @click="confirmDelete = false" class="ml-auto rounded-full px-3.5 py-1.5 text-xs font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                                <button type="button" @click="confirmDelete = false" class="rounded-full bg-rose-600 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Yes, delete</button>
                            </div>
                        </div>
                    @endforeach

                    <p x-show="noResults()" class="b-card p-8 text-center text-sm text-slate-500 sm:col-span-2">No savings plans match your filters.</p>
                </div>

                {{-- Needs Attention + Insights --}}
                <div class="space-y-6">
                    <div class="b-card p-5">
                        <h3 class="font-display text-sm font-semibold text-slate-900">Needs Attention</h3>
                        <div class="mt-3 space-y-1">
                            @foreach ($attentionItems as $item)
                                <div class="flex items-start gap-2.5 rounded-lg px-2 py-2 hover:bg-slate-50">
                                    <span class="mt-0.5 status-dot {{ $toneDot[$item->tone] ?? 'bg-blue-500' }}"></span>
                                    <div class="text-sm">
                                        <p class="font-medium text-slate-800">{{ $item->label }}</p>
                                        <p class="text-xs text-slate-500">{{ $item->meta }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

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
                </div>
            </div>
        @endif

        {{-- ============ Savings Plan Detail Drawer ============ --}}
        <template x-teleport="body">
            <div x-show="$store.planDrawer.open" x-cloak class="fixed inset-0 z-[95]" @keydown.escape.window="$store.planDrawer.open = false">
                <div class="absolute inset-0 bg-slate-900/40" @click="$store.planDrawer.open = false"></div>
                <div x-show="$store.planDrawer.open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                     class="absolute inset-y-0 right-0 flex w-full max-w-xl flex-col overflow-y-auto bg-white shadow-2xl">

                    <template x-if="$store.planDrawer.plan">
                        <div class="flex flex-1 flex-col" x-data="{ chartsRendered: false }" x-effect="if ($store.planDrawer.tab === 'analytics' && !chartsRendered) { $nextTick(() => { renderPlanCharts($store.planDrawer.plan); chartsRendered = true; }); }">
                            {{-- Hero --}}
                            <div class="border-b border-slate-100 bg-gradient-to-br from-blue-50 to-white p-6">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-display text-lg font-semibold text-slate-900" x-text="$store.planDrawer.plan.name"></h3>
                                        <p class="text-sm text-slate-500" x-text="$store.planDrawer.plan.description"></p>
                                    </div>
                                    <button @click="$store.planDrawer.open = false" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-slate-400 hover:bg-white">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                    </button>
                                </div>

                                <div class="mt-4 flex items-end justify-between">
                                    <div><p class="text-xs text-slate-400">Current</p><p class="font-mono text-2xl font-semibold text-slate-900" x-text="'₦' + Number($store.planDrawer.plan.current).toLocaleString()"></p></div>
                                    <template x-if="$store.planDrawer.plan.target">
                                        <div class="text-right"><p class="text-xs text-slate-400">Target</p><p class="font-mono text-lg font-semibold text-slate-600" x-text="'₦' + Number($store.planDrawer.plan.target).toLocaleString()"></p></div>
                                    </template>
                                </div>
                                <template x-if="$store.planDrawer.plan.target">
                                    <div class="mt-2">
                                        <div class="b-progress-track"><div class="b-progress-fill" :style="`width:${Math.min(100, Math.round(($store.planDrawer.plan.current / $store.planDrawer.plan.target) * 100))}%; background:#2563EB;`"></div></div>
                                        <p class="mt-1 text-xs font-medium text-blue-600" x-text="Math.min(100, Math.round(($store.planDrawer.plan.current / $store.planDrawer.plan.target) * 100)) + '%'"></p>
                                    </div>
                                </template>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <button @click="$store.planModal.mode='edit'; $store.planModal.data=$store.planDrawer.plan; $store.planModal.open=true"
                                            class="rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:border-slate-300">Edit Plan</button>
                                    <button @click="$store.recordModal.data = { plan: $store.planDrawer.plan.name }; $store.recordModal.open = true"
                                            class="rounded-full bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">Record Savings</button>
                                    <button @click="$store.planDrawer.confirmDelete = true" class="rounded-full border border-rose-200 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                                </div>
                                <div x-show="$store.planDrawer.confirmDelete" x-cloak x-transition class="mt-3 rounded-xl bg-rose-50 p-3.5 text-sm">
                                    <p class="font-medium text-rose-700">Delete this savings plan?</p>
                                    <p class="mt-1 text-xs text-rose-600">All savings records under this plan will be removed from this view.</p>
                                    <div class="mt-3 flex justify-end gap-2">
                                        <button @click="$store.planDrawer.confirmDelete = false" class="rounded-full px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-white">Cancel</button>
                                        <button @click="$store.planDrawer.confirmDelete = false; $store.planDrawer.open = false" class="rounded-full bg-rose-600 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Delete Plan</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Overview stat row --}}
                            <div class="grid grid-cols-4 divide-x divide-slate-100 border-b border-slate-100 text-center">
                                <div class="p-3"><p class="font-mono text-sm font-semibold text-slate-900" x-text="'₦' + Number($store.planDrawer.plan.current).toLocaleString()"></p><p class="text-[10px] text-slate-400">Total Saved</p></div>
                                <div class="p-3"><p class="font-mono text-sm font-semibold text-slate-900" x-text="$store.planDrawer.plan.target ? ('₦' + Number(Math.max(0, $store.planDrawer.plan.target - $store.planDrawer.plan.current)).toLocaleString()) : '—'"></p><p class="text-[10px] text-slate-400">Remaining</p></div>
                                <div class="p-3"><p class="font-mono text-sm font-semibold text-slate-900" x-text="$store.planDrawer.plan.records.length"></p><p class="text-[10px] text-slate-400">Deposits</p></div>
                                <div class="p-3"><p class="font-mono text-sm font-semibold text-slate-900" x-text="'₦' + ($store.planDrawer.plan.records.length ? Math.round($store.planDrawer.plan.records.reduce((s,r)=>s+Number(r.amount),0) / $store.planDrawer.plan.records.length).toLocaleString() : 0)"></p><p class="text-[10px] text-slate-400">Avg. Deposit</p></div>
                            </div>

                            {{-- Tabs --}}
                            <div class="flex gap-1 overflow-x-auto border-b border-slate-100 px-5 py-2">
                                <template x-for="t in ['overview','records','analytics','activity','settings']" :key="t">
                                    <button @click="$store.planDrawer.tab = t" :class="$store.planDrawer.tab === t ? 'segbtn active' : 'segbtn'" class="shrink-0 capitalize" x-text="t"></button>
                                </template>
                            </div>

                            <div class="flex-1 space-y-5 p-5">
                                {{-- Overview --}}
                                <div x-show="$store.planDrawer.tab === 'overview'" class="space-y-4 text-sm text-slate-600">
                                    <div class="rounded-xl bg-slate-50 p-4">
                                        <div class="flex justify-between"><span class="text-slate-400">Start date</span><span class="font-medium text-slate-800" x-text="$store.planDrawer.plan.start_date"></span></div>
                                        <div class="mt-1.5 flex justify-between" x-show="$store.planDrawer.plan.end_date"><span class="text-slate-400">End date</span><span class="font-medium text-slate-800" x-text="$store.planDrawer.plan.end_date"></span></div>
                                        <div class="mt-1.5 flex justify-between"><span class="text-slate-400">Duration</span><span class="font-medium text-slate-800" x-text="$store.planDrawer.plan.has_duration ? 'Fixed' : 'No Target Date'"></span></div>
                                    </div>
                                </div>

                                {{-- Records --}}
                                <div x-show="$store.planDrawer.tab === 'records'" class="space-y-3">
                                    <template x-for="(r, i) in $store.planDrawer.plan.records" :key="r.id">
                                        <div class="rounded-xl border border-slate-100 p-3.5">
                                            <div class="flex items-center justify-between">
                                                <span class="font-mono text-base font-semibold text-slate-900" x-text="'₦' + Number(r.amount).toLocaleString()"></span>
                                                <span class="text-xs text-slate-400" x-text="r.date"></span>
                                            </div>
                                            <p class="mt-1 text-xs text-slate-500" x-text="r.description || 'No description'"></p>
                                            <div class="mt-2 flex justify-end gap-3">
                                                <button @click="$store.recordDrawer.record = r; $store.recordDrawer.plan = $store.planDrawer.plan.name; $store.recordDrawer.open = true" class="text-xs font-semibold text-slate-500 hover:text-slate-800">View</button>
                                                <button @click="$store.recordModal.data = { ...r, plan: $store.planDrawer.plan.name }; $store.recordModal.open = true" class="text-xs font-semibold text-slate-500 hover:text-slate-800">Edit</button>
                                                <button @click="$store.planDrawer.plan.records.splice(i,1)" class="text-xs font-semibold text-rose-500 hover:text-rose-700">Delete</button>
                                            </div>
                                        </div>
                                    </template>
                                    <p x-show="!$store.planDrawer.plan.records.length" class="text-sm text-slate-400">No savings records yet.</p>
                                </div>

                                {{-- Analytics --}}
                                <div x-show="$store.planDrawer.tab === 'analytics'" class="space-y-6">
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Savings Trend</h4>
                                        <div class="mt-2 h-48"><canvas id="planTrendChart"></canvas></div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Monthly Deposits</h4>
                                        <div class="mt-2 h-40"><canvas id="planMonthlyChart"></canvas></div>
                                    </div>
                                    <template x-if="$store.planDrawer.plan.target">
                                        <div>
                                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Progress</h4>
                                            <div class="mt-3 flex items-center justify-center">
                                                <div class="relative h-32 w-32">
                                                    <svg viewBox="0 0 100 100" class="h-32 w-32 -rotate-90">
                                                        <circle cx="50" cy="50" r="42" fill="none" stroke="#EEF1F6" stroke-width="10"/>
                                                        <circle cx="50" cy="50" r="42" fill="none" stroke="#2563EB" stroke-width="10" stroke-linecap="round"
                                                                :stroke-dasharray="2 * Math.PI * 42"
                                                                :stroke-dashoffset="2 * Math.PI * 42 * (1 - Math.min(1, $store.planDrawer.plan.current / $store.planDrawer.plan.target))"/>
                                                    </svg>
                                                    <div class="absolute inset-0 flex items-center justify-center font-mono text-xl font-semibold text-slate-900" x-text="Math.min(100, Math.round(($store.planDrawer.plan.current / $store.planDrawer.plan.target) * 100)) + '%'"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                {{-- Activity --}}
                                <div x-show="$store.planDrawer.tab === 'activity'" class="space-y-2">
                                    <template x-for="(a, i) in $store.planDrawer.plan.activity" :key="i">
                                        <div class="flex items-center justify-between border-l-2 border-slate-100 pl-3 text-sm">
                                            <span class="text-slate-700" x-text="a.label"></span>
                                            <span class="text-xs text-slate-400" x-text="a.date"></span>
                                        </div>
                                    </template>
                                </div>

                                {{-- Settings --}}
                                <div x-show="$store.planDrawer.tab === 'settings'" class="space-y-2.5">
                                    <button @click="$store.planModal.mode='edit'; $store.planModal.data=$store.planDrawer.plan; $store.planModal.open=true"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-left text-sm font-medium text-slate-700 hover:border-slate-300">Edit Plan</button>
                                    <button class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-left text-sm font-medium text-slate-700 hover:border-slate-300">Archive Plan</button>
                                    <button @click="$store.planDrawer.confirmDelete = true" class="w-full rounded-xl border border-rose-200 px-4 py-2.5 text-left text-sm font-medium text-rose-600 hover:bg-rose-50">Delete Plan</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        {{-- ============ Savings Record Detail Drawer ============ --}}
        <template x-teleport="body">
            <div x-show="$store.recordDrawer.open" x-cloak class="fixed inset-0 z-[105]" @keydown.escape.window="$store.recordDrawer.open = false">
                <div class="absolute inset-0 bg-slate-900/40" @click="$store.recordDrawer.open = false"></div>
                <div x-show="$store.recordDrawer.open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     class="absolute inset-y-0 right-0 flex w-full max-w-sm flex-col overflow-y-auto bg-white shadow-2xl">
                    <template x-if="$store.recordDrawer.record">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-mono text-2xl font-semibold text-slate-900" x-text="'₦' + Number($store.recordDrawer.record.amount).toLocaleString()"></p>
                                    <p class="mt-0.5 text-sm text-slate-500" x-text="$store.recordDrawer.record.date"></p>
                                </div>
                                <button @click="$store.recordDrawer.open = false" class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <div class="mt-5 space-y-3 rounded-xl bg-slate-50 p-4 text-sm">
                                <div class="flex justify-between"><span class="text-slate-400">Plan</span><span class="font-medium text-slate-800" x-text="$store.recordDrawer.plan"></span></div>
                                <div class="flex justify-between"><span class="text-slate-400">Description</span><span class="font-medium text-slate-800" x-text="$store.recordDrawer.record.description || '—'"></span></div>
                                <div class="flex justify-between"><span class="text-slate-400">Method</span><span class="font-medium text-slate-800" x-text="$store.recordDrawer.record.method || '—'"></span></div>
                                <div class="flex justify-between"><span class="text-slate-400">Created by</span><span class="font-medium text-slate-800" x-text="$store.recordDrawer.record.created_by"></span></div>
                            </div>

                            <div class="mt-6 flex gap-2">
                                <button @click="$store.recordModal.data = { ...$store.recordDrawer.record, plan: $store.recordDrawer.plan }; $store.recordModal.open = true"
                                        class="flex-1 rounded-full border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:border-slate-300">Edit</button>
                                <button class="flex-1 rounded-full border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        {{-- ============ Quick Add Plan modal ============ --}}
        <template x-teleport="body">
            <div x-show="$store.quickAddModal.open" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4" @keydown.escape.window="$store.quickAddModal.open = false">
                <div x-show="$store.quickAddModal.open" x-transition.opacity class="absolute inset-0 bg-slate-900/50" @click="$store.quickAddModal.open = false"></div>
                <div x-show="$store.quickAddModal.open" x-transition x-data="{ name: '' }" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
                    <h3 class="font-display text-base font-semibold text-slate-900">Quick Add Plan</h3>
                    <div class="mt-4"><label class="field-label">Plan name *</label><input type="text" x-model="name" required class="field-input" placeholder="e.g. Rent Deposit"></div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="$store.quickAddModal.open = false" class="rounded-full px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                        <button type="button" @click="if (name) { $store.quickAddModal.onSave(name); $store.quickAddModal.open = false; name = ''; }"
                                class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Save Plan</button>
                    </div>
                </div>
            </div>
        </template>

        {{-- ============ Record / Edit Savings modal ============ --}}
        <template x-teleport="body">
            <div x-show="$store.recordModal.open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="$store.recordModal.open = false">
                <div x-show="$store.recordModal.open" x-transition.opacity class="absolute inset-0 bg-slate-900/40" @click="$store.recordModal.open = false"></div>
                <div x-show="$store.recordModal.open" x-transition x-data="recordForm()" x-init="init()" class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl">
                    <h3 class="font-display text-lg font-semibold text-slate-900" x-text="form.id ? 'Edit Savings Record' : 'Record Savings'"></h3>

                    <form class="mt-5 space-y-6" @submit.prevent="submit()">
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Select Plan</h4>
                            <div class="mt-3 flex gap-2">
                                <select x-model="form.plan" class="field-input flex-1">
                                    <option value="" disabled>Choose a plan</option>
                                    <template x-for="p in planNames" :key="p"><option :value="p" x-text="p"></option></template>
                                </select>
                                <button type="button" @click="$store.quickAddModal.onSave = (name) => { planNames.push(name); form.plan = name; }; $store.quickAddModal.open = true"
                                        class="shrink-0 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2.5 text-xs font-semibold text-blue-700 hover:bg-blue-100">+ Add New Plan</button>
                            </div>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Savings Information</h4>
                            <div class="mt-3 grid gap-4 sm:grid-cols-2">
                                <div><label class="field-label">Amount *</label><input type="number" min="0" x-model.number="form.amount" required class="field-input font-mono"></div>
                                <div><label class="field-label">Date *</label><input type="date" x-model="form.date" required class="field-input"></div>
                                <div class="sm:col-span-2"><label class="field-label">Description (optional)</label><input type="text" x-model="form.description" class="field-input"></div>
                                <div class="sm:col-span-2">
                                    <label class="field-label">Payment method (optional)</label>
                                    <select x-model="form.method" class="field-input"><option value="">Select method</option><option>Cash</option><option>Bank Transfer</option><option>Mobile Money</option><option>Other</option></select>
                                </div>
                            </div>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Summary</h4>
                            <div class="mt-3 grid grid-cols-3 gap-y-2 rounded-xl bg-slate-50 p-4 text-sm">
                                <div><p class="text-xs text-slate-400">Plan</p><p class="font-medium text-slate-800" x-text="form.plan || '—'"></p></div>
                                <div><p class="text-xs text-slate-400">Current Balance</p><p class="font-mono font-medium text-slate-800" x-text="'₦' + currentBalance().toLocaleString()"></p></div>
                                <div><p class="text-xs text-slate-400">New Balance</p><p class="font-mono font-semibold text-emerald-600" x-text="'₦' + (currentBalance() + Number(form.amount||0)).toLocaleString()"></p></div>
                            </div>
                        </section>

                        <p x-show="error" x-text="error" class="text-sm font-medium text-rose-600"></p>

                        <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-5">
                            <button type="button" @click="$store.recordModal.open = false" class="rounded-full px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                            <template x-if="form.id"><button type="button" class="rounded-full border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50">Delete Record</button></template>
                            <button type="submit" :disabled="submitting" class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700 disabled:opacity-60">
                                <span x-show="!submitting" x-text="form.id ? 'Update Record' : 'Record Savings'"></span>
                                <span x-show="submitting">Saving…</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- ============ Create / Edit Savings Plan modal ============ --}}
        <template x-teleport="body">
            <div x-show="$store.planModal.open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="$store.planModal.open = false">
                <div x-show="$store.planModal.open" x-transition.opacity class="absolute inset-0 bg-slate-900/40" @click="$store.planModal.open = false"></div>
                <div x-show="$store.planModal.open" x-transition x-data="planForm()" x-init="init()" class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl">

                    <h3 class="font-display text-lg font-semibold text-slate-900" x-text="$store.planModal.mode === 'edit' ? 'Edit Savings Plan' : 'Create Savings Plan'"></h3>

                    <form class="mt-5 space-y-6" @submit.prevent="submit()">
                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Basic Information</h4>
                            <div class="mt-3 space-y-3">
                                <div>
                                    <label class="field-label">Plan name *</label>
                                    <input type="text" x-model="form.name" list="existingPlanNames" required class="field-input" placeholder="e.g. Emergency Fund">
                                    <datalist id="existingPlanNames"><template x-for="p in planNames" :key="p"><option :value="p"></option></template></datalist>
                                    <p class="mt-1 text-xs text-slate-400">Start typing to see existing plan names, or enter a new one.</p>
                                </div>
                                <div><label class="field-label">Description</label><textarea x-model="form.description" rows="2" class="field-input"></textarea></div>
                            </div>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Savings Goal</h4>
                            <div class="mt-3"><label class="field-label">Target amount (optional)</label><input type="number" min="0" x-model.number="form.target" class="field-input font-mono" placeholder="₦0.00"></div>

                            <div class="mt-4 grid grid-cols-2 gap-2">
                                <button type="button" @click="form.has_duration = true" :class="form.has_duration ? 'segbtn active' : 'segbtn'">Has Duration</button>
                                <button type="button" @click="form.has_duration = false" :class="!form.has_duration ? 'segbtn active' : 'segbtn'">No Duration</button>
                            </div>

                            <div x-show="form.has_duration" x-transition class="mt-3 grid grid-cols-2 gap-3">
                                <div><label class="field-label">Start date</label><input type="date" x-model="form.start_date" class="field-input"></div>
                                <div><label class="field-label">End date</label><input type="date" x-model="form.end_date" class="field-input"></div>
                            </div>
                            <p x-show="!form.has_duration" class="mt-3"><span class="badge bg-slate-100 text-slate-500">No Target Date</span></p>
                        </section>

                        <section>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-blue-600">Preview</h4>
                            <div class="mt-3 rounded-xl bg-slate-50 p-4 text-sm">
                                <p class="font-semibold text-slate-900" x-text="form.name || '—'"></p>
                                <p class="text-slate-500" x-show="form.target" x-text="'Target ₦' + Number(form.target || 0).toLocaleString()"></p>
                                <p class="mt-1 text-xs text-slate-500" x-show="form.has_duration" x-text="(form.start_date || '—') + ' → ' + (form.end_date || '—')"></p>
                            </div>
                        </section>

                        <p x-show="error" x-text="error" class="text-sm font-medium text-rose-600"></p>

                        <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-5">
                            <button type="button" @click="$store.planModal.open = false" class="rounded-full px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                            <template x-if="$store.planModal.mode === 'edit'">
                                <button type="button" @click="deleting = true" class="rounded-full border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50">Delete Plan</button>
                            </template>
                            <button type="submit" :disabled="submitting" class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700 disabled:opacity-60">
                                <span x-show="!submitting" x-text="$store.planModal.mode === 'edit' ? 'Update Plan' : 'Create Savings Plan'"></span>
                                <span x-show="submitting">Saving…</span>
                            </button>
                        </div>

                        <div x-show="deleting" x-cloak x-transition class="flex items-center justify-end gap-3 rounded-xl bg-rose-50 p-3.5 text-sm">
                            <span class="text-rose-700">Delete this plan permanently?</span>
                            <button type="button" @click="deleting = false" class="rounded-full px-3.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-white">Cancel</button>
                            <button type="button" @click="deleting = false; $store.planModal.open = false" class="rounded-full bg-rose-600 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Yes, delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('planModal', { open: false, mode: 'create', data: null });
            Alpine.store('planDrawer', { open: false, plan: null, tab: 'overview', confirmDelete: false });
            Alpine.store('recordDrawer', { open: false, record: null, plan: null });
            Alpine.store('recordModal', { open: false, data: null });
            Alpine.store('quickAddModal', { open: false, onSave: () => {} });

            const allPlanNames = @json($plans->pluck('name'));

            Alpine.data('savingsPage', () => ({
                loading: true,
                mobileFiltersOpen: false,
                filters: { search: '', duration: 'This Month', status: 'All', plan: '', sort: 'newest' },
                sortLabels: { newest: 'Newest', oldest: 'Oldest', highest_balance: 'Highest Balance', lowest_balance: 'Lowest Balance', recently_updated: 'Recently Updated' },

                init() { setTimeout(() => { this.loading = false; }, 300); },

                isVisible(el) {
                    const name = el.dataset.name || '';
                    const status = el.dataset.status || '';
                    const f = this.filters;

                    if (f.search && !name.includes(f.search.toLowerCase())) return false;
                    if (f.status !== 'All' && status !== f.status.toLowerCase()) return false;
                    if (f.plan && !name.includes(f.plan.toLowerCase())) return false;

                    return true;
                },

                noResults() {
                    const cards = this.$el.querySelectorAll('[data-name]');
                    return cards.length > 0 && Array.from(cards).every(el => !this.isVisible(el));
                },

                renderPlanCharts(plan) {
                    const months = ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
                    const trendCtx = document.getElementById('planTrendChart');
                    if (trendCtx) {
                        new Chart(trendCtx, {
                            type: 'line',
                            data: { labels: months, datasets: [{ data: plan.trend, borderColor: '#2563EB', backgroundColor: 'rgba(37,99,235,0.08)', fill: true, tension: 0.35, pointRadius: 3 }] },
                            options: { plugins: { legend: { display: false } }, scales: { y: { ticks: { callback: v => '₦' + (v/1000) + 'k' } } } },
                        });
                    }
                    const monthlyCtx = document.getElementById('planMonthlyChart');
                    if (monthlyCtx) {
                        new Chart(monthlyCtx, {
                            type: 'bar',
                            data: { labels: months, datasets: [{ data: plan.monthly, backgroundColor: '#2563EB', borderRadius: 6, maxBarThickness: 28 }] },
                            options: { plugins: { legend: { display: false } }, scales: { y: { ticks: { callback: v => '₦' + (v/1000) + 'k' } } } },
                        });
                    }
                },
            }));

            Alpine.data('recordForm', () => ({
                submitting: false,
                error: '',
                planNames: [...allPlanNames],
                allPlansData: @json($plans),
                form: {},

                init() {
                    this.form = this.blank();
                    this.$watch('$store.recordModal.data', (val) => {
                        this.form = val ? { id: val.id || null, plan: val.plan || '', amount: val.amount || null, date: val.date || '', description: val.description || '', method: val.method || '' } : this.blank();
                        this.error = '';
                    });
                },

                blank() { return { id: null, plan: '', amount: null, date: '', description: '', method: '' }; },

                currentBalance() {
                    const p = this.allPlansData.find(p => p.name === this.form.plan);
                    return p ? Number(p.current) : 0;
                },

                submit() {
                    if (!this.form.plan || !this.form.amount || !this.form.date) {
                        this.error = 'Please fill in all required fields.';
                        return;
                    }
                    this.error = '';
                    this.submitting = true;
                    setTimeout(() => { this.submitting = false; this.$store.recordModal.open = false; }, 500);
                },
            }));

            Alpine.data('planForm', () => ({
                submitting: false,
                deleting: false,
                error: '',
                planNames: [...allPlanNames],
                form: {},

                init() {
                    this.form = this.blank();
                    this.$watch('$store.planModal.data', (val) => {
                        this.form = val ? this.hydrate(val) : this.blank();
                        this.deleting = false;
                        this.error = '';
                    });
                },

                blank() { return { name: '', description: '', target: null, has_duration: true, start_date: '', end_date: '' }; },

                hydrate(p) {
                    return { name: p.name, description: p.description || '', target: p.target || null, has_duration: p.has_duration, start_date: p.start_date || '', end_date: p.end_date || '' };
                },

                submit() {
                    if (!this.form.name) {
                        this.error = 'Please enter a plan name.';
                        return;
                    }
                    this.error = '';
                    this.submitting = true;
                    setTimeout(() => { this.submitting = false; this.$store.planModal.open = false; }, 600);
                },
            }));
        });
    </script>
</div>
