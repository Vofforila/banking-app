<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TransferHistory extends Component
{
    public ?int $accountId = null;
    public string $period = 'month';
    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public bool $showCustomRange = false;

    public function mount(int $accountId): void
    {
        $this->accountId = $accountId;
        $this->setDefaultDates();
    }

    private function setDefaultDates(): void
    {
        $now = Carbon::now();

        [$this->dateFrom, $this->dateTo] = match ($this->period) {
            'day' => [$now->startOfDay()->format('Y-m-d'), $now->endOfDay()->format('Y-m-d')],
            'week' => [$now->startOfWeek()->format('Y-m-d'), $now->endOfWeek()->format('Y-m-d')],
            'month' => [$now->startOfMonth()->format('Y-m-d'), $now->endOfMonth()->format('Y-m-d')],
            'year' => [$now->startOfYear()->format('Y-m-d'), $now->endOfYear()->format('Y-m-d')],
            default => [$now->startOfMonth()->format('Y-m-d'), $now->endOfMonth()->format('Y-m-d')],
        };
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;
        $this->showCustomRange = false;
        $this->setDefaultDates();
    }

    public function toggleCustomRange(): void
    {
        $this->showCustomRange = !$this->showCustomRange;
    }

    public function render(): view
    {
        $transactions = $this->getTransactions();

        $totalSpent = abs($transactions->where('amount', '<', 0)->sum('amount'));
        $totalIncome = $transactions->where('amount', '>', 0)->sum('amount');

        return view('livewire.transfer-history', [
            'transactions' => $transactions,
            'totalSpent' => round($totalSpent, 2),
            'totalIncome' => round($totalIncome, 2),
        ]);
    }

    public function getTransactions()
    {
        if (!$this->accountId || !$this->dateFrom || !$this->dateTo) return collect();

        $account = Account::find($this->accountId);
        if (!$account) return collect();

        return Transactions::where('user_id', auth()->id())
            ->where('account', $account->name)
            ->get()
            ->filter(function ($t) {
                $date = Carbon::createFromFormat('d.m.Y', $t->date);
                return $date->between(
                    Carbon::parse($this->dateFrom),
                    Carbon::parse($this->dateTo)
                );
            })
            ->sortByDesc(fn($t) => Carbon::createFromFormat('d.m.Y', $t->date))
            ->values();
    }
}
