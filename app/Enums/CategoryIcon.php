<?php

namespace App\Enums;

enum CategoryIcon: string
{
    case Food = 'food';
    case Transport = 'transport';
    case Electronics = 'electronics';
    case Health = 'health';
    case Paycheck = 'paycheck';
    case Gift = 'gift';
    case Interest = 'interest';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function path(): string
    {
        return asset('images/' . $this->value . '.svg');
    }
}
