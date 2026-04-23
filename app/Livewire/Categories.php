<?php

namespace App\Livewire;

use App\Enums\TransactionCategory;
use App\Models\Transactions;
use App\Models\UserCategory;
use Livewire\Attributes\On;
use Livewire\Component;

class Categories extends Component
{
    public string $type = 'expenses';

    // Edit modal
    public bool $showEditModal = false;
    public ?int $editingId = null;
    public string $editName = '';
    public string $editType = 'expenses';
    public string $editKeywords = '';

    #[On('categoryUpdated')]
    public function refreshCategories(): void
    {
        // Just having this method is enough — Livewire re-renders automatically
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function openEdit(int $id): void
    {
        $category = UserCategory::where('user_id', auth()->id())->findOrFail($id);
        $this->editingId = $id;
        $this->editName = $category->name;
        $this->editType = $category->type;
        $this->editKeywords = implode(', ', $category->keywords);
        $this->showEditModal = true;
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editName' => 'required|string',
            'editType' => 'required|in:expenses,income',
            'editKeywords' => 'required|string',
        ]);

        $keywords = array_map('trim', explode(',', $this->editKeywords));

        UserCategory::where('user_id', auth()->id())
            ->where('id', $this->editingId)
            ->update([
                'name' => $this->editName,
                'type' => $this->editType,
                'keywords' => $keywords,
            ]);

        // Recategorize transactions after edit
        $this->recategorize();

        $this->reset(['showEditModal', 'editingId', 'editName', 'editType', 'editKeywords']);
    }

    private function recategorize(): void
    {
        $transactions = Transactions::where('user_id', auth()->id())->get();

        foreach ($transactions as $transaction) {
            $newCategory = TransactionCategory::detect(
                $transaction->payer ?? '',
                $transaction->description ?? '',
                auth()->id()
            );
            $transaction->update(['category' => $newCategory]);
        }
    }

    public function delete(int $id): void
    {
        UserCategory::where('user_id', auth()->id())
            ->where('id', $id)
            ->delete();

        $this->recategorize();
    }

    public function getPredefinedCategories(): array
    {
        return array_filter(
            TransactionCategory::cases(),
            fn($c) => $c->type() === $this->type
        );
    }

    public function render()
    {
        $userCategories = UserCategory::where('user_id', auth()->id())
            ->where('type', $this->type)
            ->get();

        return view('livewire.categories', compact('userCategories'));
    }
}
