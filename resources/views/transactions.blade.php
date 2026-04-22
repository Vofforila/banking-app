<x-layouts::app.sidebar :title="'Transactions'">
    <flux:main>
        <flux:heading size="xl">Transactions</flux:heading>
        <flux:button href="{{route('transaction.add')}}" size="sm" variant="primary">Add Transaction
        </flux:button>
        <livewire:transactions-table/>
    </flux:main>
</x-layouts::app.sidebar>
