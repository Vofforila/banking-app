<?php

namespace App\Livewire;

use App\Enums\TransactionCategory;
use App\Models\Transactions;
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

    public function setType(string $type): void
    {
        $this->type = $type;
        $this->selectedCategory = null; // reset category when switching
    }

    public function setCategory(string $category): void
    {
        $this->selectedCategory = $category;
    }

    public function render(): view
    {
        return view('livewire.add-transaction', [
            'categories' => TransactionCategory::forType($this->type),
        ]);
    }

    public function save(): void
    {
        // Validate
        $this->validate([
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'selectedAccount' => 'required',
            'selectedCategory' => 'required',
            'currency' => 'required',
        ]);

        // Save to DB
        Transactions::createTransaction(
            account: $this->selectedAccount,
            type: $this->type,
            amount: $this->amount,
            currency: $this->currency,
            date: $this->formatDate($this->date),
            category: $this->selectedCategory,
            description: $this->description,
        );

        // Redirect back to transactions
        $this->redirect(route('transaction.index'));
    }

    public function formatDate(string $date): string
    {
        return Carbon::parse($date)->format('d.m.Y');
    }
}
