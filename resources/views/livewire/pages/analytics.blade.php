<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new 
#[Layout('layouts.app')]
#[Title('Analytics')]
class extends Component {
    public function with(): array
    {
        $groupsList = ['Family', 'Church cooperative', 'Office savings'];

        $months = ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
        $spendTrend = [210000, 245000, 198000, 260000, 230000, 320000];
        $savingsTrend = [180000, 240000, 310000, 420000, 520000, 640000];

        $budgetUtilization = collect([
            (object)['name' => 'Food', 'spent' => 45000, 'limit' => 60000],
            (object)['name' => 'Transport', 'spent' => 18000, 'limit' => 25000],
            (object)['name' => 'Family Utilities', 'spent' => 32000, 'limit' => 50000],
            (object)['name' => 'Church Building Fund', 'spent' => 120000, 'limit' => 150000],
            (object)['name' => 'Vacation Budget', 'spent' => 212000, 'limit' => 200000],
        ]);

        $categoryLabels = ['Food', 'Rent', 'Transport', 'Shopping', 'Utilities', 'Others'];
        $categoryData = [38, 22, 12, 10, 13, 5];
        $categoryColors = ['#2563EB', '#60A5FA', '#F97316', '#10B981', '#A855F7', '#94A3B8'];

        $billsCollected = [45000, 60000, 52000, 70000, 65000, 90000];
        $billsOutstanding = [15000, 20000, 12000, 18000, 22000, 25000];

        $groupSpend = collect(['Family' => 480000, 'Church cooperative' => 210000, 'Office savings' => 95000, 'Personal' => 260000]);

        $summaryCards = [
            ['label' => 'Total Spent', 'value' => '₦' . number_format(array_sum($spendTrend)), 'trend' => '↑ 9% vs previous period', 'up' => true, 'icon' => 'trend-up'],
            ['label' => 'Total Saved', 'value' => '₦' . number_format(end($savingsTrend)), 'trend' => '↑ 23% vs previous period', 'up' => true, 'icon' => 'piggy'],
            ['label' => 'Total Budgeted', 'value' => '₦' . number_format($budgetUtilization->sum('limit')), 'trend' => 'Across 5 budgets', 'up' => null, 'icon' => 'wallet'],
            ['label' => 'Bills Collected', 'value' => '₦' . number_format(array_sum($billsCollected)), 'trend' => '↑ 12% collection rate', 'up' => true, 'icon' => 'check'],
            ['label' => 'Bills Outstanding', 'value' => '₦' . number_format(array_sum($billsOutstanding)), 'trend' => '↓ 6% vs previous period', 'up' => false, 'icon' => 'alert'],
            ['label' => 'Avg. Budget Utilization', 'value' => round($budgetUtilization->sum('spent') / max(1, $budgetUtilization->sum('limit')) * 100) . '%', 'trend' => '1 budget over limit', 'up' => null, 'icon' => 'gauge'],
        ];

        $topCategories = collect($categoryLabels)->map(function ($label, $i) use ($categoryData) {
            return (object)['name' => $label, 'pct' => $categoryData[$i], 'trend' => [+15, -4, +8, 0, +12, -2][$i] ?? 0];
        })->sortByDesc('pct')->values();

        $attentionItems = collect([
            (object)['label' => 'Vacation Budget', 'meta' => 'Exceeded by ₦12,000', 'tone' => 'red'],
            (object)['label' => 'Church Building Fund', 'meta' => '80% used, ends in 12 days', 'tone' => 'amber'],
            (object)['label' => 'Family Rent', 'meta' => 'Bill overdue — 2 members unpaid', 'tone' => 'red'],
            (object)['label' => 'Office Savings', 'meta' => 'No deposits in 45 days', 'tone' => 'amber'],
            (object)['label' => '4 expense requests', 'meta' => 'Awaiting approval across groups', 'tone' => 'blue'],
        ]);

        $insights = [
            'You spent 9% more this period, mostly driven by the Vacation Budget overage.',
            'Family accounts for 60% of all group spending across your 3 groups.',
            'Bill collection rate improved to 78%, up from 66% last period.',
            'Savings grew 23% this period — Car Fund and New Laptop both crossed key milestones.',
            'Food remains your largest spending category at 38% of total spend.',
            'Church cooperative has the lowest contribution follow-through of your groups.',
        ];

        $toneClasses = ['blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'], 'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600'], 'red' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600']];
        $toneDot = ['blue' => 'bg-blue-500', 'amber' => 'bg-amber-500', 'red' => 'bg-rose-500'];

        return compact(
            'groupsList', 'months', 'spendTrend', 'savingsTrend', 'budgetUtilization',
            'categoryLabels', 'categoryData', 'categoryColors', 'billsCollected',
            'billsOutstanding', 'groupSpend', 'summaryCards', 'topCategories',
            'attentionItems', 'insights', 'toneClasses', 'toneDot'
        );
    }
}; ?>

<div>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4" x-data="{}">
            <div>
                <h2 class="font-display text-xl font-semibold leading-tight text-slate-900">Analytics</h2>
                <p class="mt-0.5 text-sm text-slate-500">One view across your budgets, expenses, bills, groups and savings.</p>
            </div>
            <button type="button" @click="$store.exportModal.open = true"
                    class="hidden items-center gap-2 rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 transition hover:bg-blue-700 sm:inline-flex">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/></svg>
                Export Report
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
        .segbtn{ border-radius:.75rem; border:1px solid #E2E8F0; padding:.5rem .75rem; font-size:.75rem; font-weight:600; color:#64748B; }
        .segbtn.active{ border-color:#2563EB; background:#EFF6FF; color:#1D4ED8; }
        .field-label{ font-size:.75rem; font-weight:600; color:#475569; }
        .field-input{ margin-top:.375rem; width:100%; border-radius:.75rem; border:1px solid #E2E8F0; padding:.625rem .875rem; font-size:.875rem; }
        .field-input:focus{ outline:none; border-color:#60A5FA; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .skeleton{ background: linear-gradient(90deg,#EEF1F6 25%,#F6F8FA 37%,#EEF1F6 63%); background-size:400% 100%; animation: shimmer 1.4s ease infinite; border-radius:16px; }
        @keyframes shimmer{ 0%{ background-position:100% 50%;} 100%{ background-position:0 50%;} }
    </style>

    <div x-data="analyticsPage()" x-init="init()" x-cloak class="relative mx-auto max-w-7xl space-y-8 pb-16">

        {{-- Mobile export button --}}
        <button type="button" @click="$store.exportModal.open = true"
                class="fixed bottom-6 right-6 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-300 sm:hidden">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/></svg>
        </button>

        {{-- Sticky filter bar --}}
        <div class="sticky top-[73px] z-30 -mx-2 rounded-2xl border border-slate-200 bg-white/95 p-3 backdrop-blur-md sm:mx-0">
            <div class="flex flex-wrap items-center gap-2">
                <template x-for="opt in ['This Month','Last Month','Last 3 Months','Last 6 Months','This Year']" :key="opt">
                    <button type="button" @click="filters.range = opt" :class="filters.range === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                </template>
                <span class="mx-1 hidden h-5 w-px bg-slate-200 sm:block"></span>
                <template x-for="opt in ['All','Personal','Group']" :key="opt">
                    <button type="button" @click="filters.scope = opt" :class="filters.scope === opt ? 'segbtn active' : 'segbtn'" x-text="opt"></button>
                </template>
                <select x-show="filters.scope === 'Group'" x-model="filters.group" class="rounded-full border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">
                    <option value="">All groups</option>
                    @foreach ($groupsList as $g) <option>{{ $g }}</option> @endforeach
                </select>
            </div>
        </div>

        <div x-show="loading" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <div class="skeleton h-28"></div><div class="skeleton h-28"></div><div class="skeleton h-28"></div>
            <div class="skeleton h-28"></div><div class="skeleton h-28"></div><div class="skeleton h-28"></div>
        </div>

        <div x-show="!loading" class="space-y-8">
            {{-- KPI summary cards --}}
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($summaryCards as $card)
                    <div class="b-card p-5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            @switch($card['icon'])
                                @case('trend-up')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 17 9 11l4 4 8-8M21 7h-6v6"/></svg>
                                    @break
                                @case('piggy')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 5c-4 0-7 2.5-7 6 0 1.6.6 2.7 1.5 3.6L5 17h3l.8-1a8 8 0 0 0 2.2.3c4 0 7-2.6 7-6.3S15 5 11 5Z"/><circle cx="15" cy="10" r=".8" fill="currentColor" stroke="none"/></svg>
                                    @break
                                @case('wallet')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7a2 2 0 0 1 2-2h13a1 1 0 0 1 1 1v3"/><path d="M3 7v11a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-5a2 2 0 1 0 0 4"/></svg>
                                    @break
                                @case('check')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m5 13 4 4L19 7"/></svg>
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
                            <p class="mt-2 text-xs font-medium {{ $card['up'] ? 'text-emerald-600' : 'text-rose-600' }}">{{ $card['trend'] }}</p>
                        @else
                            <p class="mt-2 text-xs font-medium text-slate-400">{{ $card['trend'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Row 1: Spending trend + Expense breakdown --}}
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="b-card p-5 lg:col-span-2">
                    <div class="flex items-center justify-between">
                        <h3 class="font-display text-sm font-semibold text-slate-900">Spending &amp; Savings Trend</h3>
                        <div class="flex items-center gap-3 text-xs text-slate-500">
                            <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-blue-500"></span>Spending</span>
                            <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Savings</span>
                        </div>
                    </div>
                    <div class="mt-4 h-64"><canvas id="trendChart"></canvas></div>
                </div>
                <div class="b-card p-5">
                    <h3 class="font-display text-sm font-semibold text-slate-900">Expense Breakdown</h3>
                    <div class="mt-4 h-64"><canvas id="categoryChart"></canvas></div>
                </div>
            </div>

            {{-- Row 2: Budget utilization + Bills collection --}}
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="b-card p-5">
                    <h3 class="font-display text-sm font-semibold text-slate-900">Budget Utilization</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Spent vs. limit across active budgets</p>
                    <div class="mt-4 h-64"><canvas id="budgetChart"></canvas></div>
                </div>
                <div class="b-card p-5">
                    <h3 class="font-display text-sm font-semibold text-slate-900">Bills Collection</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Collected vs. outstanding per month</p>
                    <div class="mt-4 h-64"><canvas id="billsChart"></canvas></div>
                </div>
            </div>

            {{-- Row 3: Savings growth + Spend by group --}}
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="b-card p-5">
                    <h3 class="font-display text-sm font-semibold text-slate-900">Savings Growth</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Cumulative balance across all plans</p>
                    <div class="mt-4 h-56"><canvas id="savingsChart"></canvas></div>
                </div>
                <div class="b-card p-5">
                    <h3 class="font-display text-sm font-semibold text-slate-900">Spend by Group</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Personal vs. each collaborative group</p>
                    <div class="mt-4 h-56"><canvas id="groupChart"></canvas></div>
                </div>
            </div>

            {{-- Row 4: Budget health, Top categories, Insights --}}
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="b-card p-5">
                    <h3 class="font-display text-sm font-semibold text-slate-900">Needs Attention</h3>
                    <div class="mt-3 space-y-1">
                        @foreach ($attentionItems as $item)
                            <div class="flex items-start gap-2.5 rounded-lg px-2 py-2 hover:bg-slate-50">
                                <span class="mt-0.5 status-dot {{ $toneDot[$item->tone] }}"></span>
                                <div class="text-sm">
                                    <p class="font-medium text-slate-800">{{ $item->label }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->meta }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="b-card p-5">
                    <h3 class="font-display text-sm font-semibold text-slate-900">Top Categories</h3>
                    <div class="mt-3 space-y-3">
                        @foreach ($topCategories->take(6) as $cat)
                            <div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-medium text-slate-700">{{ $cat->name }}</span>
                                    <span class="flex items-center gap-1.5">
                                        <span class="text-slate-500">{{ $cat->pct }}%</span>
                                        <span class="text-xs font-medium {{ $cat->trend >= 0 ? 'text-rose-500' : 'text-emerald-600' }}">{{ $cat->trend >= 0 ? '↑' : '↓' }}{{ abs($cat->trend) }}%</span>
                                    </span>
                                </div>
                                <div class="b-progress-track mt-1.5"><div class="b-progress-fill" style="width:{{ $cat->pct }}%; background:#2563EB;"></div></div>
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

        {{-- ============ Export Report modal ============ --}}
        <template x-teleport="body">
            <div x-show="$store.exportModal.open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="$store.exportModal.open = false">
                <div x-show="$store.exportModal.open" x-transition.opacity class="absolute inset-0 bg-slate-900/40" @click="$store.exportModal.open = false"></div>
                <div x-show="$store.exportModal.open" x-transition x-data="exportForm()" class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                    <h3 class="font-display text-lg font-semibold text-slate-900">Export Report</h3>
                    <p class="mt-0.5 text-sm text-slate-500">Download a snapshot of this analytics view.</p>

                    <div class="mt-5 space-y-4">
                        <div>
                            <label class="field-label">Format</label>
                            <div class="mt-1.5 grid grid-cols-2 gap-2">
                                <button type="button" @click="format = 'PDF'" :class="format === 'PDF' ? 'segbtn active' : 'segbtn'">PDF</button>
                                <button type="button" @click="format = 'CSV'" :class="format === 'CSV' ? 'segbtn active' : 'segbtn'">CSV</button>
                            </div>
                        </div>
                        <div>
                            <label class="field-label">Date range</label>
                            <select x-model="range" class="field-input">
                                <option>This Month</option><option>Last Month</option><option>Last 3 Months</option><option>Last 6 Months</option><option>This Year</option>
                            </select>
                        </div>
                        <div>
                            <label class="field-label">Include sections</label>
                            <div class="mt-2 space-y-2">
                                <template x-for="s in sections" :key="s">
                                    <label class="flex items-center gap-2 text-sm text-slate-700">
                                        <input type="checkbox" :value="s" x-model="included" class="h-4 w-4 rounded border-slate-300 text-blue-600">
                                        <span x-text="s"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                        <button type="button" @click="$store.exportModal.open = false" class="rounded-full px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancel</button>
                        <button type="button" @click="download()" :disabled="downloading" class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200 hover:bg-blue-700 disabled:opacity-60">
                            <span x-show="!downloading">Download</span>
                            <span x-show="downloading">Preparing…</span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('exportModal', { open: false });

            Alpine.data('analyticsPage', () => ({
                loading: true,
                filters: { range: 'Last 6 Months', scope: 'All', group: '' },

                init() {
                    setTimeout(() => { this.loading = false; this.$nextTick(() => this.renderCharts()); }, 350);
                },

                renderCharts() {
                    new Chart(document.getElementById('trendChart'), {
                        type: 'line',
                        data: {
                            labels: @json($months),
                            datasets: [
                                { label: 'Spending', data: @json($spendTrend), borderColor: '#2563EB', backgroundColor: 'rgba(37,99,235,0.08)', fill: true, tension: 0.35, pointRadius: 3 },
                                { label: 'Savings', data: @json($savingsTrend), borderColor: '#10B981', backgroundColor: 'rgba(16,185,129,0.06)', fill: true, tension: 0.35, pointRadius: 3 },
                            ],
                        },
                        options: { plugins: { legend: { display: false } }, scales: { y: { ticks: { callback: v => '₦' + (v/1000) + 'k' } } } },
                    });

                    new Chart(document.getElementById('categoryChart'), {
                        type: 'doughnut',
                        data: { labels: @json($categoryLabels), datasets: [{ data: @json($categoryData), backgroundColor: @json($categoryColors), borderWidth: 0 }] },
                        options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }, cutout: '65%' },
                    });

                    new Chart(document.getElementById('budgetChart'), {
                        type: 'bar',
                        data: {
                            labels: @json($budgetUtilization->pluck('name')),
                            datasets: [
                                { label: 'Spent', data: @json($budgetUtilization->pluck('spent')), backgroundColor: '#2563EB', borderRadius: 6, maxBarThickness: 22 },
                                { label: 'Limit', data: @json($budgetUtilization->pluck('limit')), backgroundColor: '#DBEAFE', borderRadius: 6, maxBarThickness: 22 },
                            ],
                        },
                        options: { indexAxis: 'y', plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }, scales: { x: { ticks: { callback: v => '₦' + (v/1000) + 'k' } } } },
                    });

                    new Chart(document.getElementById('billsChart'), {
                        type: 'bar',
                        data: {
                            labels: @json($months),
                            datasets: [
                                { label: 'Collected', data: @json($billsCollected), backgroundColor: '#10B981', borderRadius: 6, maxBarThickness: 22 },
                                { label: 'Outstanding', data: @json($billsOutstanding), backgroundColor: '#FCA5A5', borderRadius: 6, maxBarThickness: 22 },
                            ],
                        },
                        options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }, scales: { x: { stacked: true }, y: { stacked: true, ticks: { callback: v => '₦' + (v/1000) + 'k' } } } },
                    });

                    new Chart(document.getElementById('savingsChart'), {
                        type: 'line',
                        data: { labels: @json($months), datasets: [{ data: @json($savingsTrend), borderColor: '#10B981', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.35, pointRadius: 3 }] },
                        options: { plugins: { legend: { display: false } }, scales: { y: { ticks: { callback: v => '₦' + (v/1000) + 'k' } } } },
                    });

                    new Chart(document.getElementById('groupChart'), {
                        type: 'bar',
                        data: {
                            labels: @json($groupSpend->keys()),
                            datasets: [{ data: @json($groupSpend->values()), backgroundColor: '#2563EB', borderRadius: 6, maxBarThickness: 26 }],
                        },
                        options: { indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { ticks: { callback: v => '₦' + (v/1000) + 'k' } } } },
                    });
                },
            }));

            Alpine.data('exportForm', () => ({
                format: 'PDF',
                range: 'Last 6 Months',
                sections: ['Budgets', 'Expenses', 'Bills', 'Groups', 'Savings'],
                included: ['Budgets', 'Expenses', 'Bills', 'Groups', 'Savings'],
                downloading: false,

                download() {
                    this.downloading = true;
                    setTimeout(() => { this.downloading = false; this.$store.exportModal.open = false; }, 700);
                },
            }));
        });
    </script>
</div>
