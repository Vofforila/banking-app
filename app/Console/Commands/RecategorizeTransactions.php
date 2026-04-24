<?php

namespace App\Console\Commands;

use App\Models\Transactions;
use App\Models\UserCategory;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:recategorize-transactions')]
#[Description('Command description')]
class RecategorizeTransactions extends Command
{
    protected $signature = 'transactions:recategorize {--user= : Recategorize only a specific user ID}';
    protected $description = 'Recategorize all transactions based on current keyword definitions';

    public function handle(): void
    {
        $query = Transactions::query();

        if ($userId = $this->option('user')) {
            $query->where('user_id', $userId);
        }

        $transactions = $query->get();
        $updated = 0;

        $this->info("Processing {$transactions->count()} transactions...");
        $bar = $this->output->createProgressBar($transactions->count());

        foreach ($transactions as $transaction) {
            $newCategory = UserCategory::detect(
                $transaction->payer ?? '',
                $transaction->description ?? '',
                $transaction->user_id
            );

            if ($newCategory !== $transaction->category) {
                $transaction->update(['category' => $newCategory]);
                $updated++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Done! Updated {$updated} transactions.");
    }
}
