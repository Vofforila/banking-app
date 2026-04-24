<div class="space-y-6">

    @if($accounts->isEmpty())
        {{-- No accounts state --}}
        <div class="flex flex-col items-center justify-center py-24 text-center space-y-4">
            <div class="text-6xl">🏦</div>
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">No accounts yet</h2>
            <p class="text-zinc-400 max-w-sm">
                Your accounts are automatically created when you upload a bank statement or add a transaction manually.
            </p>
            <div class="flex gap-3 mt-2">
                <flux:button href="{{ route('dashboard') }}" variant="primary" size="sm">
                    📂 Upload Statement
                </flux:button>
                <flux:button href="{{ route('transaction.add') }}" variant="outline" size="sm">
                    ➕ Add Transaction
                </flux:button>
            </div>
        </div>

    @else
        {{-- Account Selector --}}
        <div class="flex items-center gap-3">
            <flux:select wire:model.live="selectedAccountId" class="flex-1">
                <flux:select.option value="" disabled>Select an account...</flux:select.option>
                @foreach($accounts as $account)
                    <flux:select.option value="{{ $account->id }}">
                        {{ $account->name }} — {{ $account->iban ?? 'No IBAN' }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            @if($selectedAccountId)
                <flux:button
                    wire:click="setAsDefault"
                    size="sm"
                    variant="{{ $defaultAccountId === $selectedAccountId ? 'primary' : 'outline' }}">
                    {{ $defaultAccountId === $selectedAccountId ? '★ Default' : '☆ Set as Default' }}
                </flux:button>
            @endif
        </div>

        @if($selectedAccount)

            {{-- Total Balance --}}
            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-8 border border-zinc-200 dark:border-zinc-700">
                <div class="flex flex-col items-center justify-center text-center">
                    <p class="text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-2">Total Balance</p>
                    <p class="text-5xl font-bold {{ $selectedAccount->total_balance >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        {{ number_format($selectedAccount->total_balance, 2) }}
                        <span class="text-2xl font-normal text-zinc-400">{{ $selectedAccount->currency }}</span>
                    </p>

                    {{-- Action Buttons --}}
                    <div class="flex justify-center gap-3 mt-6">
                        <flux:button wire:click="openHistory" variant="outline" size="sm">
                            📋 Transfer History
                        </flux:button>
                        <flux:button href="{{ route('transaction.add', ['account' => $selectedAccount->name]) }}"
                                     variant="primary" size="sm">
                            ➕ New Transfer
                        </flux:button>
                    </div>

                    {{-- Transfer History Modal --}}
                    @if($showHistoryModal)
                        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                            <div
                                class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">

                                {{-- Modal Header --}}
                                <div
                                    class="flex items-center justify-between p-6 border-b border-zinc-200 dark:border-zinc-700">
                                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                        Transfer History — {{ $selectedAccount->name }}
                                    </h2>
                                    <flux:button wire:click="closeHistory" variant="outline" size="sm" icon="x-mark"/>
                                </div>

                                {{-- Modal Body — scrollable --}}
                                <div class="overflow-y-auto flex-1 p-6">
                                    <livewire:transfer-history
                                        :accountId="$selectedAccountId"
                                        :key="'history-'.$selectedAccountId"/>
                                </div>

                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Stats Row --}}
            <div class="grid grid-cols-3 gap-4">
                <div
                    class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-5 border border-zinc-200 dark:border-zinc-700 text-center">
                    <p class="text-xs text-zinc-400 uppercase tracking-widest mb-1">Transactions</p>
                    <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">
                        {{ $selectedAccount->total_transactions }}
                    </p>
                </div>
                <div
                    class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-5 border border-zinc-200 dark:border-zinc-700 text-center">
                    <p class="text-xs text-zinc-400 uppercase tracking-widest mb-1">Total Spent</p>
                    <p class="text-3xl font-bold text-red-500">
                        {{ number_format($selectedAccount->total_spent, 2) }}
                        <span class="text-sm font-normal text-zinc-400">{{ $selectedAccount->currency }}</span>
                    </p>
                </div>
                <div
                    class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-5 border border-zinc-200 dark:border-zinc-700 text-center">
                    <p class="text-xs text-zinc-400 uppercase tracking-widest mb-1">Total Income</p>
                    <p class="text-3xl font-bold text-green-500">
                        {{ number_format($selectedAccount->total_income, 2) }}
                        <span class="text-sm font-normal text-zinc-400">{{ $selectedAccount->currency }}</span>
                    </p>
                </div>
            </div>

            {{-- Category Breakdown --}}
            @if(count($categoryBreakdown) > 0)
                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-700">
                    <p class="text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-4">Category Breakdown</p>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($categoryBreakdown as $category => $data)
                            <div
                                class="bg-white dark:bg-zinc-700 rounded-xl p-4 border border-zinc-100 dark:border-zinc-600">

                                {{-- Category header --}}
                                <div class="flex items-center gap-2 mb-3">
                                    <x-category-icon :category="$category" :size="8"/>
                                    <p class="font-semibold text-zinc-900 dark:text-zinc-100 truncate">{{ $category }}</p>
                                </div>

                                {{-- Total --}}
                                <p class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                                    {{ number_format($data['sum'], 2) }}
                                    <span
                                        class="text-xs font-normal text-zinc-400">{{ $selectedAccount->currency }}</span>
                                </p>

                                {{-- Spent / Income --}}
                                <div class="flex justify-between mt-1">
                                    @if($data['spent'] > 0)
                                        <span
                                            class="text-xs text-red-500">-{{ number_format($data['spent'], 2) }}</span>
                                    @endif
                                    @if($data['income'] > 0)
                                        <span
                                            class="text-xs text-green-500">+{{ number_format($data['income'], 2) }}</span>
                                    @endif
                                </div>

                                {{-- Percentage + count --}}
                                <p class="text-xs text-zinc-400 mt-1">{{ $data['percentage'] }}% · {{ $data['count'] }}
                                    transactions</p>

                                {{-- Progress bar --}}
                                <div class="mt-2 h-1.5 bg-zinc-200 dark:bg-zinc-600 rounded-full">
                                    <div class="h-1.5 bg-blue-500 rounded-full"
                                         style="width: {{ $data['percentage'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @else
            <div class="text-center py-16 text-zinc-400">
                <p class="text-lg">No account selected</p>
                <p class="text-sm mt-1">Select an account from the dropdown above</p>
            </div>
        @endif
    @endif
</div>
