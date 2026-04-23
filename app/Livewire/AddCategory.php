<?php

namespace App\Livewire;

use App\Enums\TransactionCategory;
use App\Models\Transactions;
use App\Models\UserCategory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class AddCategory extends Component
{
    public bool $showModal = false;
    public string $name = '';
    public string $type = 'expenses';
    public string $keywordsInput = ''; // comma separated input

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string',
            'type' => 'required|in:expenses,income',
            'keywordsInput' => 'required|string',
        ]);

        $keywords = array_map('trim', explode(',', $this->keywordsInput));

        UserCategory::updateOrCreate(
            ['user_id' => auth()->id(), 'name' => $this->name],
            ['type' => $this->type, 'keywords' => $keywords]
        );

        // Recategorize
        $transactions = Transactions::where('user_id', auth()->id())->get();
        foreach ($transactions as $transaction) {
            $newCategory = TransactionCategory::detect(
                $transaction->payer ?? '',
                $transaction->description ?? '',
                auth()->id()
            );
            $transaction->update(['category' => $newCategory]);
        }

        $this->reset(['name', 'type', 'keywordsInput', 'showModal']);

        // ✅ Tell other components a category was added
        $this->dispatch('categoryUpdated');
    }

    public function render(): view
    {
        $categories = UserCategory::where('user_id', auth()->id())->get();
        return view('livewire.add-category', compact('categories'));
    }
}
