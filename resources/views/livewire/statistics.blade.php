<div class="p-6 space-y-6">

    {{-- View Toggle --}}
    <div class="flex gap-2">
        <flux:button wire:click="setView('general')" variant="{{ $view === 'general'  ? 'primary' : 'outline' }}"
                     size="sm">📊 General
        </flux:button>
        <flux:button wire:click="setView('expenses')" variant="{{ $view === 'expenses' ? 'primary' : 'outline' }}"
                     size="sm">💸 Expenses
        </flux:button>
        <flux:button wire:click="setView('income')" variant="{{ $view === 'income'   ? 'primary' : 'outline' }}"
                     size="sm">💰 Income
        </flux:button>
    </div>

    {{-- Period Toggle --}}
    <div class="flex gap-2">
        @foreach(['year' => 'Year', 'month' => 'Month', 'week' => 'Week', 'day' => 'Day'] as $key => $label)
            <flux:button wire:click="setPeriod('{{ $key }}')" variant="{{ $period === $key ? 'primary' : 'outline' }}"
                         size="sm">
                {{ $label }}
            </flux:button>
        @endforeach
    </div>

    {{-- Category Filter --}}
    <div class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-4 border border-zinc-200 dark:border-zinc-700">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold tracking-widest uppercase text-zinc-400">Filter Categories</p>
            <div class="flex gap-2">
                <flux:button wire:click="selectAll" size="sm" variant="outline">All</flux:button>
                <flux:button wire:click="deselectAll" size="sm" variant="outline">None</flux:button>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($this->getAllCategories() as $category)
                <label class="flex items-center gap-2 cursor-pointer bg-white dark:bg-zinc-700 rounded-lg px-3 py-2 border select-none
                {{ in_array($category, $selectedCategories) ? 'border-blue-400' : 'border-zinc-200 dark:border-zinc-600 opacity-50' }}">
                    <input
                        type="checkbox"
                        wire:model.live="selectedCategories"
                        value="{{ $category }}"
                        class="rounded"/>
                    <span class="text-sm text-zinc-900 dark:text-zinc-100">{{ $category }}</span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Chart --}}
    <div class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-700">
        @if(count($chartData) === 0)
            <p class="text-zinc-400 text-center py-12">No data available.</p>
        @else
            @php
                $max = max(array_map(fn($d) => max($d['expenses'], $d['income']), $chartData));
                $max = $max > 0 ? $max : 1;

                // ✅ Round max up to nearest nice number divisible by 5
                $magnitude = pow(10, floor(log10($max)));
                $nice = ceil($max / ($magnitude * 5)) * ($magnitude * 5);
                $max = $nice;

                $chartHeight = 180;
                $steps = 5;
            @endphp

            <div class="flex gap-4">

                {{-- Y Axis Labels --}}
                <div class="flex flex-col justify-between items-end pb-6" style="min-height: {{ $chartHeight + 20 }}px">
                    @for($i = $steps; $i >= 0; $i--)
                        @php $value = round(($max / $steps) * $i) @endphp
                        <span class="text-xs text-zinc-400">{{ number_format($value) }}</span>
                    @endfor
                </div>

                {{-- Vertical divider --}}
                <div class="w-px bg-zinc-200 dark:bg-zinc-600 self-stretch mb-6"></div>

                {{-- Bars --}}
                <div class="flex items-end gap-3 overflow-x-auto pb-4 flex-1"
                     style="min-height: {{ $chartHeight + 20 }}px">
                    @foreach($chartData as $bar)
                        @php
                            $expHeight = (int)(($bar['expenses'] / $max) * $chartHeight);
                            $incHeight = (int)(($bar['income']   / $max) * $chartHeight);
                            $isSelected = $selectedPeriod === $bar['label'];
                        @endphp

                        <div
                            wire:click="selectPeriod('{{ $bar['label'] }}')"
                            class="flex flex-col items-center gap-1 cursor-pointer group min-w-[60px]">

                            {{-- Bars --}}
                            <div class="flex items-end gap-1">
                                @if($view !== 'income')
                                    <div
                                        style="height: {{ $expHeight }}px; width: 20px;"
                                        class="rounded-t-md transition-all {{ $isSelected ? 'bg-red-400' : 'bg-red-300 group-hover:bg-red-400' }}">
                                    </div>
                                @endif
                                @if($view !== 'expenses')
                                    <div
                                        style="height: {{ $incHeight }}px; width: 20px;"
                                        class="rounded-t-md transition-all {{ $isSelected ? 'bg-green-500' : 'bg-green-300 group-hover:bg-green-500' }}">
                                    </div>
                                @endif
                            </div>

                            {{-- Label --}}
                            <span
                                class="text-xs text-zinc-400 text-center {{ $isSelected ? 'text-blue-500 font-semibold' : '' }}">
                            {{ $bar['label'] }}
                        </span>
                        </div>
                    @endforeach
                </div>

            </div>

            {{-- Horizontal divider --}}
            <div class="h-px bg-zinc-200 dark:bg-zinc-600 mb-3 ml-10"></div>

            {{-- Legend --}}
            <div class="flex gap-4 ml-10">
                @if($view !== 'income')
                    <div class="flex items-center gap-1">
                        <div class="w-3 h-3 rounded bg-red-300"></div>
                        <span class="text-xs text-zinc-400">Expenses</span>
                    </div>
                @endif
                @if($view !== 'expenses')
                    <div class="flex items-center gap-1">
                        <div class="w-3 h-3 rounded bg-green-300"></div>
                        <span class="text-xs text-zinc-400">Income</span>
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if($selectedPeriod && count($selectedData) > 0)
        <div class="bg-zinc-50 dark:bg-zinc-800 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-700">
            <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-4">
                {{ $selectedPeriod }} — Breakdown
            </h3>

            @if(!$selectedCategory)
                {{-- Category Cards --}}
                <div class="flex items-center gap-3 mb-2">
                    <x-category-icon :category="$category" :size="8"/>
                    <p class="font-semibold">{{ $category }}</p>
                </div>

            @else
                {{-- Transaction List for selected category --}}
                <div class="flex items-center gap-2 mb-4">
                    <flux:button wire:click="$set('selectedCategory', null)" variant="outline" size="sm">
                        ← Back
                    </flux:button>
                    <h4 class="font-semibold text-zinc-900 dark:text-zinc-100">
                        {{ $selectedCategory }} — {{ $selectedPeriod }}
                    </h4>
                </div>

                <div class="space-y-2">
                    @foreach($categoryTransactions as $transaction)
                        <div
                            class="flex items-center justify-between bg-white dark:bg-zinc-700 rounded-xl px-4 py-3 border border-zinc-100 dark:border-zinc-600">
                            <div>
                                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $transaction['payer'] ?: 'N/A' }}
                                </p>
                                <p class="text-xs text-zinc-400">{{ $transaction['date'] }}</p>
                            </div>
                            <p class="font-bold {{ $transaction['amount'] < 0 ? 'text-red-500' : 'text-green-500' }}">
                                {{ $transaction['amount'] }} {{ $transaction['currency'] }}
                            </p>
                        </div>
                    @endforeach

                    {{-- Add Transaction Button --}}
                    <div class="flex justify-center pt-4">
                        <a href="{{ route('transaction.add') }}">
                            <div
                                class="w-12 h-12 rounded-full bg-blue-500 hover:bg-blue-600 flex items-center justify-center text-white text-2xl shadow-lg transition-colors cursor-pointer">
                                +
                            </div>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    @endif

</div>
