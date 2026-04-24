<?php

namespace App\Livewire;

use App\Models\Transactions;
use App\Models\UserCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AddCategory extends Component
{
    public bool $showModal = false;
    public string $name = '';
    public string $type = 'expenses';
    public string $keywordsInput = '';
    public string $selectedIcon = 'food';
    public string $selectedColor = '#3b82f6';

    public function save(): void
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('user_categories', 'name')
                    ->where('user_id', auth()->id()),
            ],
            'type' => 'required|in:expenses,income',
            'keywordsInput' => 'required|string',
        ]);

        $keywords = array_map('trim', explode(',', $this->keywordsInput));

        UserCategory::updateOrCreate(
            ['user_id' => auth()->id(), 'name' => $this->name],
            [
                'type' => $this->type,
                'keywords' => $keywords,
                'icon' => $this->selectedIcon,
                'color' => $this->selectedColor,
            ]
        );

        $transactions = Transactions::where('user_id', auth()->id())->get();
        foreach ($transactions as $transaction) {
            $newCategory = UserCategory::detect(
                $transaction->payer ?? '',
                $transaction->description ?? '',
                auth()->id()
            );
            $transaction->update(['category' => $newCategory]);
        }

        $this->reset(['name', 'type', 'keywordsInput', 'showModal']);

        $this->dispatch('categoryUpdated');
    }

    public function render(): view
    {
        $categories = UserCategory::where('user_id', auth()->id())->get();
        return view('livewire.add-category', compact('categories'));
    }

    public function onIconSelected(string $icon, string $color): void
    {
        $this->selectedIcon = $icon;
        $this->selectedColor = $color;
    }

    protected function getListeners()
    {
        return ['iconSelected' => 'onIconSelected'];
    }
}
