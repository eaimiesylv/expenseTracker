<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public $budgets;
    public $bills;
    public $groups;
    public $recentExpenses;
    public $totalBalance;
    public $monthSpend;
    public $activeBudgets;
    public $budgetsTotalLimit;
    public $budgetsTotalSpent;
    public $billsPendingTotal;
    public $groupsNeedingAttention;
    public $attentionItems;
    public $upcomingEvents;

    public function mount(): void
    {
        $this->budgets = collect([
            (object)['name' => 'Food', 'spent' => 45000, 'limit' => 60000, 'type' => 'Personal'],
            (object)['name' => 'Transport', 'spent' => 18000, 'limit' => 25000, 'type' => 'Personal'],
            (object)['name' => 'Family utilities', 'spent' => 32000, 'limit' => 50000, 'type' => 'Group'],
            (object)['name' => 'Church building fund', 'spent' => 120000, 'limit' => 150000, 'type' => 'Group'],
            (object)['name' => 'Data & subscriptions', 'spent' => 21000, 'limit' => 15000, 'type' => 'Personal'],
        ]);

        $this->bills = collect([
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

        $this->groups = collect([
            (object)['name' => 'Family', 'members' => 15, 'paid' => 12],
            (object)['name' => 'Church cooperative', 'members' => 40, 'paid' => 40],
            (object)['name' => 'Office savings', 'members' => 8, 'paid' => 3],
        ]);

        $this->recentExpenses = collect([
            (object)['item' => 'Rice (bag)', 'category' => 'Food', 'budget' => 'Food', 'amount' => 32000, 'date' => 'Jul 12'],
            (object)['item' => 'Fuel', 'category' => 'Transport', 'budget' => 'Transport', 'amount' => 8000, 'date' => 'Jul 11'],
            (object)['item' => 'NEPA bill', 'category' => 'Utilities', 'budget' => 'Family utilities', 'amount' => 15000, 'date' => 'Jul 9'],
        ]);

        $this->totalBalance = 250000;
        $this->monthSpend = 82500;
        $this->activeBudgets = 3;

        $this->budgetsTotalLimit = $this->budgets->sum('limit');
        $this->budgetsTotalSpent = $this->budgets->sum('spent');

        $this->billsPendingTotal = $this->bills->sum(function ($bill) {
            $shares = collect($bill->participants);
            $pendingShare = $shares->count() > 0 ? $bill->total / $shares->count() : 0;
            return $shares->where('status', 'pending')->count() * $pendingShare;
        });

        $this->groupsNeedingAttention = $this->groups->filter(fn ($g) => $g->paid < $g->members)->count();

        $this->attentionItems = collect()
            ->merge($this->budgets->filter(fn ($b) => $b->spent > $b->limit)->map(fn ($b) => (object)[
                'label' => "{$b->name} is over budget",
                'meta' => '₦' . number_format($b->spent - $b->limit) . ' over',
                'href' => '#',
            ]))
            ->merge($this->bills->filter(fn ($bill) => collect($bill->participants)->contains('status', 'pending'))->map(function ($bill) {
                $pendingNames = collect($bill->participants)->where('status', 'pending')->pluck('name')->implode(', ');
                return (object)[
                    'label' => "\"{$bill->title}\" waiting on {$pendingNames}",
                    'meta' => 'Unpaid',
                    'href' => '#',
                ];
            }))
            ->merge($this->groups->filter(fn ($g) => $g->paid < $g->members)->map(fn ($g) => (object)[
                'label' => "{$g->name} cycle still collecting",
                'meta' => ($g->members - $g->paid) . ' unpaid',
                'href' => '#',
            ]));

        $this->upcomingEvents = collect([
            (object)['title' => 'Family utilities budget renews', 'date' => 'Jul 20', 'type' => 'budget'],
            (object)['title' => '"Office lunch" bill due', 'date' => 'Jul 18', 'type' => 'bill'],
            (object)['title' => 'Family group contribution due', 'date' => 'Jul 22', 'type' => 'group'],
            (object)['title' => 'Church cooperative cycle closes', 'date' => 'Jul 31', 'type' => 'group'],
        ])->sortBy(fn ($e) => Carbon::parse($e->date . ' ' . now()->year))->values();
    }

    public function render(): View
    {
        return view('livewire.pages.dashboard');
    }
}
