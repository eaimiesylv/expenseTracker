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
         $bills (collection), $groupContribution (object|null) --}}

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
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Group contribution</p>
                <p class="font-mono mt-2 text-2xl font-semibold text-slate-900">12 / 15</p>
                <p class="mt-1 text-xs text-slate-500">Members paid this cycle</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Budgets --}}
            <div class="receipt-card p-6 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <h3 class="font-display text-base font-semibold text-slate-900">Your budgets</h3>
                    <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">View all</a>
                </div>

                <div class="mt-5 space-y-5">
                    @forelse (($budgets ?? collect([
                        (object)['name' => 'Food', 'spent' => 45000, 'limit' => 60000, 'type' => 'Personal'],
                        (object)['name' => 'Transport', 'spent' => 18000, 'limit' => 25000, 'type' => 'Personal'],
                        (object)['name' => 'Family utilities', 'spent' => 32000, 'limit' => 50000, 'type' => 'Group'],
                    ])) as $budget)
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

            {{-- Group / Ajo contribution --}}
            <div class="receipt-card p-6">
                <h3 class="font-display text-base font-semibold text-slate-900">Weekly savings cycle</h3>
                <p class="mt-1 text-xs text-slate-500">Family Group · Ajo style</p>

                <div class="mt-5 rounded-xl bg-slate-50 p-4">
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span>Expected per member</span>
                        <span class="font-mono font-semibold text-slate-900">₦5,000</span>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-sm text-slate-600">
                        <span>Members paid</span>
                        <span class="font-mono font-semibold text-emerald-600">12 / 15</span>
                    </div>
                    <div class="mt-3 h-2 rounded-full bg-slate-200">
                        <div class="h-2 rounded-full" style="width:80%; background:var(--indigo);"></div>
                    </div>
                </div>

                <a href="#" class="grad-cta mt-5 block rounded-full px-4 py-2.5 text-center text-sm font-semibold text-white">Record contribution</a>
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

                <div class="mt-4 space-y-4">
                    @forelse (($bills ?? collect([
                        (object)['title' => 'Dinner', 'total' => 80000, 'participants' => [
                            (object)['name' => 'Ada', 'status' => 'paid'],
                            (object)['name' => 'Bayo', 'status' => 'pending'],
                            (object)['name' => 'Chika', 'status' => 'paid'],
                        ]],
                    ])) as $bill)
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