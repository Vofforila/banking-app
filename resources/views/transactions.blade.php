<x-layouts::app.sidebar :title="'Transactions'">
    <flux:main class="min-h-screen">
        {{-- Header Row --}}
        <flux:heading class="mb-4" size="xl">Transactions</flux:heading>
        <div>
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
