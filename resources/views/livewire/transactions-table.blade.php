<flux:table :paginate="$transactions">
    <flux:table.columns>
        <flux:table.column>Account</flux:table.column>
        <flux:table.column>IBAN</flux:table.column>
        <flux:table.column
            sortable
            :sorted="$sortBy === 'date'"
            :direction="$sortDirection"
            wire:click="sort('date')">
            Date
        </flux:table.column>
        <flux:table.column>Payer</flux:table.column>
        <flux:table.column>PayerIBAN</flux:table.column>
        <flux:table.column
            sortable
            :sorted="$sortBy === 'amount'"
            :direction="$sortDirection"
            wire:click="sort('amount')">
            Amount
        </flux:table.column>
        <flux:table.column>Currency</flux:table.column>
    </flux:table.columns>


    <flux:table.rows>
        @foreach ($transactions as $transaction)
            <flux:table.row :key="$transaction->id">
                <flux:table.cell variant="strong">{{ $transaction->account }}</flux:table.cell>
                <flux:table.cell>{{ $transaction->iban }}</flux:table.cell>
                <flux:table.cell>{{ $transaction->date }}</flux:table.cell>
                <flux:table.cell>{{ !empty($transaction->payer) ? $transaction->payer : 'N/A' }}</flux:table.cell>
                <flux:table.cell>{{ !empty($transaction->payeriban) ? $transaction->payeriban : 'N/A' }}</flux:table.cell>
                <flux:table.cell>{{ $transaction->amount }}</flux:table.cell>
                <flux:table.cell>{{ $transaction->currency }}</flux:table.cell>
            </flux:table.row>
        @endforeach
    </flux:table.rows>
</flux:table>
