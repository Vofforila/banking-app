<?php

namespace App\Livewire;

use App\Models\Transactions;
use App\Services\AccountService;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionsTable extends Component
{
    use WithPagination;

    public string $sortBy = 'date';
    public string $sortDirection = 'desc';

    public function sort($column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render(): view
    {
        $transactions = Transactions::where('user_id', auth()->id())
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
        return view('livewire.transactions-table', compact('transactions'));
    }

    public function deleteAll(): void
    {
        Transactions::where('user_id', auth()->id())->delete();
        app(AccountService::class)->syncAccounts(auth()->id());
        auth()->user()->update(['default_account_id' => null]);
    }

    public function delete(int $id): void
    {
        Transactions::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        app(AccountService::class)->syncAccounts(auth()->id());
    }
}
