<?php

namespace App\Enums;

use App\Models\UserCategory;

enum TransactionCategory: string
{
    // Expenses
    case Food = 'Food';
    case Transport = 'Transport';
    case Electronics = 'Electronics';
    case Health = 'Health';

    // Income
    case Paycheck = 'Paycheck';
    case Gift = 'Gift';
    case Interest = 'Interest';

    public static function forType(string $type): array
    {
        return array_filter(
            self::cases(),
            fn($case) => $case->type() === $type
        );
    }

    // ✅ Match a payer/description to a category

    public function type(): string
    {
        return match ($this) {
            self::Food,
            self::Transport,
            self::Electronics,
            self::Health => 'expenses',

            self::Paycheck,
            self::Gift,
            self::Interest => 'income',
        };
    }

    public static function detect(string $payer, string $description = '', ?int $userId = null): string
    {
        $text = strtolower($payer . ' ' . $description);

        // ✅ Check user defined categories FIRST — they take priority
        if ($userId) {
            $userCategories = UserCategory::where('user_id', $userId)->get();

            foreach ($userCategories as $category) {
                foreach ($category->keywords as $keyword) {
                    if (str_contains($text, strtolower($keyword))) {
                        return $category->name;
                    }
                }
            }
        }

        // ✅ Fall back to predefined categories
        foreach (self::cases() as $category) {
            foreach ($category->keywords() as $keyword) {
                if (str_contains($text, strtolower($keyword))) {
                    return $category->value;
                }
            }
        }

        return 'Uncategorized';
    }

    public function keywords(): array
    {
        return match ($this) {
            self::Food => [
                'restaurant', 'food', 'kebap', 'kfc', 'mcdonald',
                'pizza', 'burger', 'cafe', 'coffee', 'bolt food',
                'glovo', 'tazz', 'kaufland', 'lidl', 'mega image',
            ],
            self::Transport => [
                'bolt', 'uber', 'taxi', 'metrorex', 'stb',
                'ratt', 'autogara', 'cfr', 'train', 'bus',
            ],
            self::Electronics => [
                'emag', 'altex', 'flanco', 'apple', 'samsung',
                'media galaxy', 'pcgarage',
            ],
            self::Health => [
                'farmacie', 'pharmacy', 'doctor', 'clinica',
                'spital', 'hospital', 'omniasig', 'asigurare',
            ],
            self::Paycheck => [
                'salary', 'salariu', 'wage', 'payroll',
            ],
            self::Gift => [
                'gift', 'cadou', 'transfer',
            ],
            self::Interest => [
                'interest', 'dobanda', 'dividend',
            ],
        };
    }

    public function icon(): CategoryIcon
    {
        return match ($this) {
            self::Food => CategoryIcon::Food,
            self::Transport => CategoryIcon::Transport,
            self::Electronics => CategoryIcon::Electronics,
            self::Health => CategoryIcon::Health,
            self::Paycheck => CategoryIcon::Paycheck,
            self::Gift => CategoryIcon::Gift,
            self::Interest => CategoryIcon::Interest,
            self::Freelance => CategoryIcon::Freelance,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Food => '#f97316',
            self::Transport => '#3b82f6',
            self::Electronics => '#8b5cf6',
            self::Health => '#ef4444',
            self::Paycheck => '#22c55e',
            self::Gift => '#ec4899',
            self::Interest => '#14b8a6',
            self::Freelance => '#f59e0b',
        };
    }

    public function image(): string
    {
        return match ($this) {
            self::Food => 'food.png',
            self::Transport => 'transport.png',
            self::Electronics => 'electronics.png',
            self::Health => 'health.png',
            self::Paycheck => 'paycheck.png',
            self::Gift => 'gift.png',
            self::Interest => 'interest.png',
        };
    }
}
