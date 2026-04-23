<?php

namespace App\Livewire;

use App\Enums\TransactionCategory;
use App\Models\Transactions;
use App\Models\UserCategory;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Statistics extends Component
{
    public string $view = 'general';
    public string $period = 'month';
    public ?string $selectedPeriod = null;
    public array $selectedCategories = [];
    public ?string $selectedCategory = null;
    public bool $allSelected = true;

    public function render(): view
    {
        return view('livewire.statistics', [
            'chartData' => $this->getChartData(),
            'selectedData' => $this->getSelectedData(),
            'categoryTransactions' => $this->getCategoryTransactions(),
        ]);
    }

    public function getChartData(): array
    {
        $query = Transactions::where('user_id', auth()->id())
            ->whereIn('category', $this->selectedCategories); // ✅ filter

        if ($this->view === 'expenses') {
            $query->where('amount', '<', 0);
        } elseif ($this->view === 'income') {
            $query->where('amount', '>', 0);
        }

        $transactions = $query->get();

        $grouped = $transactions->groupBy(function ($t) {
            $date = Carbon::createFromFormat('d.m.Y', $t->date);
            return match ($this->period) {
                'year' => $date->format('Y'),
                'month' => $date->format('M Y'),
                'week' => 'Week ' . $date->weekOfYear . ' ' . $date->format('Y'),
                'day' => $date->format('d M Y'),
            };
        });

        $data = [];
        foreach ($grouped as $label => $items) {
            $expenses = abs($items->where('amount', '<', 0)->sum('amount'));
            $income = $items->where('amount', '>', 0)->sum('amount');

            $data[] = [
                'label' => $label,
                'expenses' => round($expenses, 2),
                'income' => round($income, 2),
                'total' => round($income - $expenses, 2),
            ];
        }

        return $data;
    }

    public function getSelectedData(): array
    {
        if (!$this->selectedPeriod) return [];

        $query = Transactions::where('user_id', auth()->id())
            ->whereIn('category', $this->selectedCategories); // ✅ filter

        if ($this->view === 'expenses') {
            $query->where('amount', '<', 0);
        } elseif ($this->view === 'income') {
            $query->where('amount', '>', 0);
        }

        $transactions = $query->get()->filter(function ($t) {
            $date = Carbon::createFromFormat('d.m.Y', $t->date);
            $label = match ($this->period) {
                'year' => $date->format('Y'),
                'month' => $date->format('M Y'),
                'week' => 'Week ' . $date->weekOfYear . ' ' . $date->format('Y'),
                'day' => $date->format('d M Y'),
            };
            return $label === $this->selectedPeriod;
        });

        $total = $transactions->sum(fn($t) => abs($t->amount));

        $byCategory = $transactions->groupBy('category')->map(function ($items) use ($total) {
            $sum = $items->sum(fn($t) => abs($t->amount));
            return [
                'sum' => round($sum, 2),
                'percentage' => $total > 0 ? round(($sum / $total) * 100, 1) : 0,
                'count' => $items->count(),
            ];
        });

        return $byCategory->toArray();
    }

    public function getCategoryTransactions(): array
    {
        if (!$this->selectedPeriod || !$this->selectedCategory) return [];

        return Transactions::where('user_id', auth()->id())
            ->where('category', $this->selectedCategory)
            ->get()
            ->filter(function ($t) {
                $date = Carbon::createFromFormat('d.m.Y', $t->date);
                $label = match ($this->period) {
                    'year' => $date->format('Y'),
                    'month' => $date->format('M Y'),
                    'week' => 'Week ' . $date->weekOfYear . ' ' . $date->format('Y'),
                    'day' => $date->format('d M Y'),
                };
                return $label === $this->selectedPeriod;
            })
            ->values()
            ->toArray();
    }

    public function mount(): void
    {
        $this->selectedCategories = $this->getAllCategories();
    }

    public function getAllCategories(): array
    {
        $predefined = array_map(fn($c) => $c->value, TransactionCategory::cases());
        $userDefined = UserCategory::where('user_id', auth()->id())->pluck('name')->toArray();
        return array_values(array_unique(array_merge($predefined, $userDefined)));
    }

    public function selectAll(): void
    {
        $this->selectedCategories = $this->getAllCategories();
    }

    public function deselectAll(): void
    {
        $this->selectedCategories = [];
    }

    public function toggleCategory(string $category): void
    {
        if (in_array($category, $this->selectedCategories)) {
            $this->selectedCategories = array_values(
                array_filter($this->selectedCategories, fn($c) => $c !== $category)
            );
        } else {
            $this->selectedCategories[] = $category;
        }

        // ✅ Recalculate allSelected based on actual state
        $this->allSelected = count($this->selectedCategories) === count($this->getAllCategories());
    }

    public function selectPeriod(string $period): void
    {
        $this->selectedPeriod = $this->selectedPeriod === $period ? null : $period;
        $this->selectedCategory = null;
    }

    public function selectCategory(string $category): void
    {
        $this->selectedCategory = $this->selectedCategory === $category ? null : $category;
    }

    public function setView(string $view): void
    {
        $this->view = $view;
        $this->selectedPeriod = null;
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;
        $this->selectedPeriod = null;
    }

}
