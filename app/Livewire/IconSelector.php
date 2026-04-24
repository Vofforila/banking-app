<?php

namespace App\Livewire;

use App\Enums\CategoryIcon;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class IconSelector extends Component
{
    public string $selectedIcon = 'food';
    public string $selectedColor = '#3b82f6';

    public array $colors = [
        '#3b82f6', '#ef4444', '#22c55e', '#f97316',
        '#8b5cf6', '#ec4899', '#14b8a6', '#f59e0b',
        '#06b6d4', '#84cc16', '#e11d48', '#7c3aed',
    ];

    public function mount(string $selectedIcon = 'food', string $selectedColor = '#3b82f6'): void
    {
        $this->selectedIcon = $selectedIcon;
        $this->selectedColor = $selectedColor;
    }

    public function selectIcon(string $icon): void
    {
        $this->selectedIcon = $icon;
        $this->dispatch('iconSelected', icon: $icon, color: $this->selectedColor);
    }

    public function selectColor(string $color): void
    {
        $this->selectedColor = $color;
        $this->dispatch('iconSelected', icon: $this->selectedIcon, color: $color);
    }

    public function render(): view
    {
        return view('livewire.icon-selector', [
            'icons' => CategoryIcon::cases(),
        ]);
    }
}
