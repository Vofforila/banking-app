<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Transactions;
use App\Models\UserCategory;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class AddTransaction extends Component
{
    public string $selectedAccount = '';
    public string $type = 'expenses';
    public ?string $selectedCategory = null;
    public ?string $amount = null;
    public string $currency = 'RON';
    public ?string $date = null;
    public ?string $description = null;

    public function mount(): void
    {
        if (request()->has('account')) {
            $this->selectedAccount = request()->get('account');
        }
    }

    public function render(): view
    {
        $accounts = Account::where('user_id', auth()->id())->get();
        $categories = $this->getCategories();

        return view('livewire.add-transaction', [
            'categories' => $categories,
            'accounts' => $accounts,
        ]);
    }

    public function getCategories(): array
    {
        return UserCategory::where('user_id', auth()->id())
            ->where('type', $this->type)
            ->get()
            ->map(fn($c) => [
                'name' => $c->name,
                'icon' => $c->icon,
                'color' => $c->color,
            ])
            ->toArray();
    }

    public function setCategory(string $category): void
    {
        $this->selectedCategory = $category;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
        $this->selectedCategory = null;
    }

    public function selectCategory(string $category): void
    {
        $this->selectedCategory = $this->selectedCategory === $category ? null : $category;
    }


    public function save(): void
    {
        $this->validate([
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'selectedAccount' => 'required',
            'selectedCategory' => 'required',
            'currency' => 'required',
        ]);

        Transactions::createTransaction(
            account: $this->selectedAccount,
            type: $this->type,
            amount: $this->amount,
            currency: $this->currency,
            date: $this->formatDate($this->date),
            category: $this->selectedCategory,
            description: $this->description,
        );

        $this->redirect(route('transactions.index'));
    }

    public function formatDate(string $date): string
    {
        return Carbon::parse($date)->format('d.m.Y');
    }
}
