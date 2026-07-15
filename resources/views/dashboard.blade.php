<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-xl font-semibold leading-tight text-slate-900">
            {{ __('Dashboard') }}
        </h2>
        <p class="mt-0.5 text-sm text-slate-500">{{ __('Here\'s what\'s happening with your money.') }}</p>
    </x-slot>

    {{-- Assumes each of these is passed from a controller / Livewire component:
         $totalBalance, $monthSpend, $activeBudgets, $pendingBillsTotal,
         $budgets (collection), $recentExpenses (collection),
         $bills (collection), $groups (collection) --}}

    @php
        // Fallback sample data so the page renders standalone — replace with real queries.
        $budgets = $budgets ?? collect([
            (object)['name' => 'Food', 'spent' => 45000, 'limit' => 60000, 'type' => 'Personal'],
            (object)['name' => 'Transport', 'spent' => 18000, 'limit' => 25000, 'type' => 'Personal'],
            (object)['name' => 'Family utilities', 'spent' => 32000, 'limit' => 50000, 'type' => 'Group'],
            (object)['name' => 'Church building fund', 'spent' => 120000, 'limit' => 150000, 'type' => 'Group'],
            (object)['name' => 'Data & subscriptions', 'spent' => 21000, 'limit' => 15000, 'type' => 'Personal'],
        ]);

        $bills = $bills ?? collect([
            (object)['title' => 'Dinner', 'total' => 80000, 'participants' => [
                (object)['name' => 'Ada', 'status' => 'paid'],
                (object)['name' => 'Bayo', 'status' => 'pending'],
                (object)['name' => 'Chika', 'status' => 'paid'],
            ]],
            (object)['title' => 'Office lunch', 'total' => 45000, 'participants' => [
                (object)['name' => 'Ifeoma', 'status' => 'paid'],
                (object)['name' => 'Tunde', 'status' => 'pending'],
            ]],
            (object)['title' => 'Uber to airport', 'total' => 18000, 'participants' => [
                (object)['name' => 'Ada', 'status' => 'pending'],
                (object)['name' => 'Segun', 'status' => 'pending'],
            ]],
        ]);

        $groups = $groups ?? collect([
            (object)['name' => 'Family', 'members' => 15, 'paid' => 12],
            (object)['name' => 'Church cooperative', 'members' => 40, 'paid' => 40],
            (object)['name' => 'Office savings', 'members' => 8, 'paid' => 3],
        ]);

        $budgetsOverLimit = $budgets->filter(fn ($b) => $b->spent > $b->limit)->count();
        $budgetsTotalLimit = $budgets->sum('limit');
        $budgetsTotalSpent = $budgets->sum('spent');

        $billsPendingCount = $bills->sum(fn ($b) => collect($b->participants)->where('status', 'pending')->count());
        $billsPendingTotal = $bills->sum(function ($b) {
            $shares = collect($b->participants);
            $pendingShare = $shares->count() > 0 ? $b->total / $shares->count() : 0;
            return $shares->where('status', 'pending')->count() * $pendingShare;
        });

        $groupsNeedingAttention = $groups->filter(fn ($g) => $g->paid < $g->members)->count();

        // Needs attention: pull the specific items behind the aggregate counts above.
        $attentionItems = collect()
            ->merge($budgets->filter(fn ($b) => $b->spent > $b->limit)->map(fn ($b) => (object)[
                'label' => "{$b->name} is over budget",
                'meta' => '₦' . number_format($b->spent - $b->limit) . ' over',
                'href' => '#',
            ]))
            ->merge($bills->filter(fn ($bill) => collect($bill->participants)->contains('status', 'pending'))->map(function ($bill) {
                $pendingNames = collect($bill->participants)->where('status', 'pending')->pluck('name')->implode(', ');
                return (object)[
                    'label' => "\"{$bill->title}\" waiting on {$pendingNames}",
                    'meta' => 'Unpaid',
                    'href' => '#',
                ];
            }))
            ->merge($groups->filter(fn ($g) => $g->paid < $g->members)->map(fn ($g) => (object)[
                'label' => "{$g->name} cycle still collecting",
                'meta' => ($g->members - $g->paid) . ' unpaid',
                'href' => '#',
            ]));

        // Upcoming: recurring budget renewals, bill due dates, group cycle deadlines.
        $upcomingEvents = $upcomingEvents ?? collect([
            (object)['title' => 'Family utilities budget renews', 'date' => 'Jul 20', 'type' => 'budget'],
            (object)['title' => '"Office lunch" bill due', 'date' => 'Jul 18', 'type' => 'bill'],
            (object)['title' => 'Family group contribution due', 'date' => 'Jul 22', 'type' => 'group'],
            (object)['title' => 'Church cooperative cycle closes', 'date' => 'Jul 31', 'type' => 'group'],
        ])->sortBy(fn ($e) => \Carbon\Carbon::parse($e->date . ' ' . now()->year))->values();
    @endphp

    <div class="mx-auto max-w-7xl space-y-8">

        {{-- Overview stat cards --}}
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <div class="receipt-card p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total balance</p>
                <p class="font-mono mt-2 text-2xl font-semibold text-slate-900">₦{{ number_format($totalBalance ?? 250000) }}</p>
                <p class="mt-1 text-xs text-emerald-600">+4.2% vs last month</p>
            </div>
            <div class="receipt-card p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Spent this month</p>
                <p class="font-mono mt-2 text-2xl font-semibold text-slate-900">₦{{ number_format($monthSpend ?? 82500) }}</p>
                <p class="mt-1 text-xs text-slate-500">Across {{ $activeBudgets ?? 3 }} active budgets</p>
            </div>
            <div class="receipt-card p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Pending bills</p>
                <p class="font-mono mt-2 text-2xl font-semibold text-slate-900">₦{{ number_format($pendingBillsTotal ?? 20000) }}</p>
                <p class="mt-1 text-xs text-amber-600">2 people yet to pay</p>
            </div>
            <div class="receipt-card p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Active groups</p>
                <p class="font-mono mt-2 text-2xl font-semibold text-slate-900">{{ $groups->count() }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ $groupsNeedingAttention }} still collecting this cycle</p>
            </div>
        </div>

        {{-- Summary: rollup across all budgets, bills, and groups --}}
        <div class="receipt-card p-6">
            <h3 class="font-display text-base font-semibold text-slate-900">Summary</h3>
            <p class="mt-0.5 text-sm text-slate-500">A rollup across all your budgets, bills, and groups.</p>

            <div class="mt-5 grid gap-6 divide-y divide-slate-100 sm:grid-cols-3 sm:divide-y-0 sm:divide-x">
                {{-- Budgets summary --}}
                <div class="pt-5 first:pt-0 sm:pt-0 sm:pr-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-800">Budgets</p>
                        <span class="chip rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-xs text-slate-500">{{ $budgets->count() }} total</span>
                    </div>
                    <p class="font-mono mt-3 text-xl font-semibold text-slate-900">₦{{ number_format($budgetsTotalSpent) }} <span class="text-sm font-normal text-slate-400">/ ₦{{ number_format($budgetsTotalLimit) }}</span></p>
                    <p class="mt-1 text-xs text-slate-500">spent of total limit</p>
                </div>

                {{-- Bills summary --}}
                <div class="pt-5 sm:pt-0 sm:px-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-800">Bills &amp; splits</p>
                        <span class="chip rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-xs text-slate-500">{{ $bills->count() }} total</span>
                    </div>
                    <p class="font-mono mt-3 text-xl font-semibold text-slate-900">₦{{ number_format($billsPendingTotal) }}</p>
                    <p class="mt-1 text-xs text-slate-500">still pending across {{ $bills->count() }} {{ Str::plural('bill', $bills->count()) }}</p>
                </div>

                {{-- Groups summary --}}
                <div class="pt-5 sm:pt-0 sm:pl-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-800">Groups</p>
                        <span class="chip rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-xs text-slate-500">{{ $groups->count() }} total</span>
                    </div>
                    <p class="font-mono mt-3 text-xl font-semibold text-slate-900">{{ $groups->sum('paid') }} <span class="text-sm font-normal text-slate-400">/ {{ $groups->sum('members') }} paid</span></p>
                    <p class="mt-1 text-xs text-slate-500">across {{ $groups->count() }} active {{ Str::plural('cycle', $groups->count()) }}</p>
                </div>
            </div>
        </div>

        {{-- Needs attention (only shows if there's something to flag) + Upcoming --}}
        <div class="grid gap-6 {{ $attentionItems->isNotEmpty() ? 'lg:grid-cols-2' : '' }}">
            @if ($attentionItems->isNotEmpty())
                <div class="receipt-card p-6">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                        <h3 class="font-display text-base font-semibold text-slate-900">Needs attention</h3>
                    </div>
                    <div class="mt-4 max-h-56 space-y-1 overflow-y-auto pr-1">
                        @foreach ($attentionItems as $item)
                            <a href="{{ $item->href }}" class="flex items-center justify-between rounded-lg px-2 py-2 text-sm hover:bg-slate-50">
                                <span class="text-slate-700">{{ $item->label }}</span>
                                <span class="ml-3 shrink-0 text-xs font-medium text-rose-600">{{ $item->meta }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="receipt-card p-6">
                <h3 class="font-display text-base font-semibold text-slate-900">Upcoming</h3>
                <div class="mt-4 max-h-56 space-y-1 overflow-y-auto pr-1">
                    @forelse ($upcomingEvents as $event)
                        @php
                            $iconBg = match ($event->type) {
                                'budget' => 'bg-emerald-50 text-emerald-600',
                                'bill' => 'bg-indigo-50 text-indigo-600',
                                default => 'bg-amber-50 text-amber-600',
                            };
                        @endphp
                        <div class="flex items-center gap-3 rounded-lg px-2 py-2 text-sm">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-semibold {{ $iconBg }}">
                                {{ strtoupper(substr($event->type, 0, 1)) }}
                            </span>
                            <span class="flex-1 text-slate-700">{{ $event->title }}</span>
                            <span class="font-mono shrink-0 text-xs text-slate-400">{{ $event->date }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Nothing coming up.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Budgets --}}
            <div class="receipt-card p-6 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <h3 class="font-display text-base font-semibold text-slate-900">Your budgets</h3>
                    <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">View all</a>
                </div>

                <div class="mt-5 max-h-80 space-y-5 overflow-y-auto pr-1">
                    @forelse ($budgets as $budget)
                        @php $pct = $budget->limit > 0 ? min(100, round(($budget->spent / $budget->limit) * 100)) : 0; @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-700">{{ $budget->name }}</span>
                                <span class="chip rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-xs text-slate-500">{{ $budget->type }}</span>
                            </div>
                            <div class="mt-2 flex items-center gap-3">
                                <div class="h-2 flex-1 rounded-full bg-slate-100">
                                    <div class="h-2 rounded-full" style="width:{{ $pct }}%; background: linear-gradient(90deg, rgba(16,185,129,0.5), #10B981);"></div>
                                </div>
                                <span class="font-mono w-28 shrink-0 text-right text-xs text-slate-500">₦{{ number_format($budget->spent) }} / ₦{{ number_format($budget->limit) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No budgets yet — create one to start tracking.</p>
                    @endforelse
                </div>
            </div>

            {{-- Groups / Ajo-Esusu cycles --}}
            <div class="receipt-card p-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-display text-base font-semibold text-slate-900">Group cycles</h3>
                    <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">View all</a>
                </div>

                <div class="mt-4 max-h-80 space-y-4 overflow-y-auto pr-1">
                    @forelse ($groups as $group)
                        @php $groupPct = $group->members > 0 ? min(100, round(($group->paid / $group->members) * 100)) : 0; @endphp
                        <div class="rounded-xl bg-slate-50 p-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-800">{{ $group->name }}</span>
                                <span class="font-mono text-xs text-slate-500">{{ $group->paid }} / {{ $group->members }} paid</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-slate-200">
                                <div class="h-2 rounded-full" style="width:{{ $groupPct }}%; background:var(--indigo);"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No groups yet.</p>
                    @endforelse
                </div>

                <a href="#" class="grad-cta mt-4 block rounded-full px-4 py-2.5 text-center text-sm font-semibold text-white">Record contribution</a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Recent expenses --}}
            <div class="receipt-card p-6 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <h3 class="font-display text-base font-semibold text-slate-900">Recent expenses</h3>
                    <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">View all</a>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs uppercase tracking-wide text-slate-400">
                                <th class="pb-2 font-medium">Item</th>
                                <th class="pb-2 font-medium">Category</th>
                                <th class="pb-2 font-medium">Budget</th>
                                <th class="pb-2 text-right font-medium">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse (($recentExpenses ?? collect([
                                (object)['item' => 'Rice (bag)', 'category' => 'Food', 'budget' => 'Food', 'amount' => 32000, 'date' => 'Jul 12'],
                                (object)['item' => 'Fuel', 'category' => 'Transport', 'budget' => 'Transport', 'amount' => 8000, 'date' => 'Jul 11'],
                                (object)['item' => 'NEPA bill', 'category' => 'Utilities', 'budget' => 'Family utilities', 'amount' => 15000, 'date' => 'Jul 9'],
                            ])) as $expense)
                                <tr>
                                    <td class="py-2.5">
                                        <p class="font-medium text-slate-800">{{ $expense->item }}</p>
                                        <p class="text-xs text-slate-400">{{ $expense->date }}</p>
                                    </td>
                                    <td class="py-2.5 text-slate-500">{{ $expense->category }}</td>
                                    <td class="py-2.5 text-slate-500">{{ $expense->budget }}</td>
                                    <td class="font-mono py-2.5 text-right font-medium text-slate-800">₦{{ number_format($expense->amount) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-center text-slate-500">No expenses recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Split bills --}}
            <div class="receipt-card p-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-display text-base font-semibold text-slate-900">Split payments</h3>
                    <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">View all</a>
                </div>

                <div class="mt-4 max-h-96 space-y-4 overflow-y-auto pr-1">
                    @forelse ($bills as $bill)
                        <div class="rounded-xl bg-slate-50 p-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-800">{{ $bill->title }}</p>
                                <p class="font-mono text-sm text-slate-500">₦{{ number_format($bill->total) }}</p>
                            </div>
                            <div class="mt-3 space-y-1.5">
                                @foreach ($bill->participants as $p)
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-slate-600">{{ $p->name }}</span>
                                        @if ($p->status === 'paid')
                                            <span class="text-xs font-semibold text-emerald-600">Paid</span>
                                        @else
                                            <span class="text-xs font-semibold text-amber-600">Pending</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No split bills yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>