<x-layouts::app.sidebar :title="'Transactions'">
    <flux:main>
        {{-- Header Row --}}
        <flux:heading size="xl">Transactions</flux:heading>
        <div class="flex items-center justify-between mb-4">
            {{-- All buttons grouped together on the right --}}
            <div class="flex items-center gap-2">
                <livewire:add-category/>
                <flux:button href="{{ route('transaction.add') }}" size="sm" variant="primary">
                    Add Transaction
                </flux:button>
            </div>
        </div>
        <livewire:transactions-table/>
    </flux:main>
</x-layouts::app.sidebar>
