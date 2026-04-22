<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $fillable = [
        'user_id',
        'account',
        'iban',
        'date',
        'payer',
        'payeriban',
        'amount',
        'currency',
        'description',
        'category'
    ];

    public static function createTransaction(
        string  $account,
        string  $type,
        float   $amount,
        string  $currency,
        string  $date,
        string  $category,
        ?string $iban = null,
        ?string $payer = null,
        ?string $payeriban = null,
        ?string $description = null,
    ): self
    {
        return self::create([
            'user_id' => auth()->id(),
            'account' => $account,
            'iban' => $iban,
            'date' => $date,
            'payer' => $payer,
            'payeriban' => $payeriban,
            'amount' => $type === 'expenses' ? -abs($amount) : abs($amount),
            'currency' => $currency,
            'description' => $description,
            'category' => $category,
        ]);
    }
}
