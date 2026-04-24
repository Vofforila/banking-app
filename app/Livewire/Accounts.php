<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Transactions;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Accounts extends Component
{
    public ?int $selectedAccountId = null;
    public bool $showHistoryModal = false;

    public function openHistory(): void
    {
        $this->showHistoryModal = true;
    }

    public function closeHistory(): void
    {
        $this->showHistoryModal = false;
    }

    public function mount(): void
    {
        $user = auth()->user();

        $this->selectedAccountId = $user->default_account_id
            ?? Account::where('user_id', $user->id)->first()?->id;
    }

    public function selectAccount(int $id): void
    {
        $this->selectedAccountId = $id;
    }

    public function setAsDefault(): void
    {
        auth()->user()->update([
            'default_account_id' => $this->selectedAccountId,
        ]);
    }

    public function render(): view
    {
        $accounts = Account::where('user_id', auth()->id())->get();

        return view('livewire.accounts', [
            'accounts' => $accounts,
            'selectedAccount' => $this->getSelectedAccount(),
            'categoryBreakdown' => $this->getCategoryBreakdown(),
            'defaultAccountId' => auth()->user()->default_account_id,
        ]);
    }

    public function getSelectedAccount(): ?Account
    {
        if (!$this->selectedAccountId) return null;

        return Account::where('user_id', auth()->id())
            ->find($this->selectedAccountId);
    }

    public function getCategoryBreakdown(): array
    {
        $account = $this->getSelectedAccount();
        if (!$account) return [];

        $transactions = Transactions::where('user_id', auth()->id())
            ->where('account', $account->name)
            ->get();

        $total = $transactions->sum(fn($t) => abs($t->amount));

        return $transactions
            ->groupBy('category')
            ->map(function ($items) use ($total) {
                $sum = $items->sum(fn($t) => abs($t->amount));
                $spent = abs($items->where('amount', '<', 0)->sum('amount'));
                $income = $items->where('amount', '>', 0)->sum('amount');

                return [
                    'sum' => round($sum, 2),
                    'spent' => round($spent, 2),
                    'income' => round($income, 2),
                    'percentage' => $total > 0 ? round(($sum / $total) * 100, 1) : 0,
                    'count' => $items->count(),
                ];
            })
            ->sortByDesc('sum')
            ->toArray();
    }
}
