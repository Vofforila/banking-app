@php use Carbon\Carbon; @endphp
<div class="space-y-4">

    {{-- Period Toggle --}}
    <div class="flex items-center justify-between">
        <div class="flex gap-2">
            @foreach(['day' => 'Day', 'week' => 'Week', 'month' => 'Month', 'year' => 'Year'] as $key => $label)
                <flux:button
                    wire:click="setPeriod('{{ $key }}')"
                    variant="{{ $period === $key ? 'primary' : 'outline' }}"
                    size="sm">
                    {{ $label }}
                </flux:button>
            @endforeach
        </div>

        {{-- Custom Range Button --}}
        <flux:button
            wire:click="toggleCustomRange"
            variant="{{ $showCustomRange ? 'primary' : 'outline' }}"
            size="sm"
            icon="calendar">
            Custom Range
        </flux:button>
    </div>

    {{-- Custom Date Range --}}
    @if($showCustomRange)
        <div
            class="flex items-center gap-3 bg-zinc-50 dark:bg-zinc-800 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700">
            <div class="flex-1">
                <flux:label>From</flux:label>
                <flux:input type="date" wire:model.live="dateFrom"/>
            </div>
            <div class="pt-5 text-zinc-400">→</div>
            <div class="flex-1">
                <flux:label>To</flux:label>
                <flux:input type="date" wire:model.live="dateTo"/>
            </div>
        </div>
    @endif

    {{-- Date Range Display --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-zinc-400">
            {{ Carbon::parse($dateFrom)->format('d M Y') }}
            →
            {{ Carbon::parse($dateTo)->format('d M Y') }}
        </p>
        <p class="text-sm text-zinc-400">{{ $transactions->count() }} transactions</p>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-zinc-50 dark:bg-zinc-800 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700 text-center">
            <p class="text-xs text-zinc-400 uppercase tracking-widest mb-1">Spent</p>
            <p class="text-2xl font-bold text-red-500">{{ number_format($totalSpent, 2) }}</p>
        </div>
        <div class="bg-zinc-50 dark:bg-zinc-800 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700 text-center">
            <p class="text-xs text-zinc-400 uppercase tracking-widest mb-1">Income</p>
            <p class="text-2xl font-bold text-green-500">{{ number_format($totalIncome, 2) }}</p>
        </div>
    </div>

    {{-- Transaction List --}}
    @if($transactions->isEmpty())
        <div class="text-center py-12 text-zinc-400">
            <p>No transactions found for this period.</p>
        </div>
    @else
        <div class="space-y-2">
            @foreach($transactions as $transaction)
                <div
                    class="flex items-center justify-between bg-white dark:bg-zinc-800 rounded-xl px-4 py-3 border border-zinc-100 dark:border-zinc-700">
                    <div class="flex items-center gap-3">
                        <x-category-icon :category="$transaction->category" :size="8"/>
                        <div>
                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $transaction->payer ?: $transaction->description ?: 'N/A' }}
                            </p>
                            <p class="text-xs text-zinc-400">
                                {{ $transaction->date }} · {{ $transaction->category }}
                            </p>
                        </div>
                    </div>
                    <p class="font-bold {{ $transaction->amount < 0 ? 'text-red-500' : 'text-green-500' }}">
                        {{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif

</div>
