<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'iban',
        'total_balance',
        'total_spent',
        'total_income',
        'total_transactions',
        'currency',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'iban', 'iban');
    }
    
    public function recalculate(): void
    {
        $transactions = Transactions::where('user_id', $this->user_id)
            ->where('account', $this->name)
            ->get();

        $spent = abs($transactions->where('amount', '<', 0)->sum('amount'));
        $income = $transactions->where('amount', '>', 0)->sum('amount');

        $this->update([
            'total_spent' => round($spent, 2),
            'total_income' => round($income, 2),
            'total_balance' => round($income - $spent, 2),
            'total_transactions' => $transactions->count(),
        ]);
    }
}
