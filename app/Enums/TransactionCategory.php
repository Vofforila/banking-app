<?php


namespace App\Enums;

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
                'ratt', 'autogara', 'cfr', 'train',
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

    public function icon(): string
    {
        return match ($this) {
            self::Food => 'food',
            self::Transport => 'transport',
            self::Electronics => 'electronics',
            self::Health => 'health',
            self::Paycheck => 'paycheck',
            self::Gift => 'gift',
            self::Interest => 'interest',
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
        };
    }
}
