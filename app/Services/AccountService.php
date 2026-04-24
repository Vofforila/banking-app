<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transactions;

class AccountService
{
    public function syncAccounts(int $userId): void
    {
        Account::where('user_id', $userId)
            ->whereNotIn('name', function ($query) use ($userId) {
                $query->select('account')
                    ->from('transactions')
                    ->where('user_id', $userId)
                    ->whereNotNull('account');
            })
            ->delete();

        $accounts = Transactions::where('user_id', $userId)
            ->select('account', 'iban', 'currency')
            ->distinct()
            ->get();

        foreach ($accounts as $accountData) {
            if (empty($accountData->account)) continue;

            $account = Account::firstOrCreate(
                ['user_id' => $userId, 'name' => $accountData->account],
                ['iban' => $accountData->iban, 'currency' => $accountData->currency ?? 'RON']
            );

            $account->recalculate();
        }
    }
}
