<div class="max-w-lg mx-auto py-8 px-4 space-y-6">

    <flux:heading size="xl" class="text-center">Add Transaction</flux:heading>

    {{-- Expense / Income Toggle --}}
    <div class="grid grid-cols-2 gap-3">
        <flux:button
            wire:click="setType('expenses')"
            variant="{{ $type === 'expenses' ? 'primary' : 'outline' }}"
            class="w-full">
            💸 Expenses
        </flux:button>
        <flux:button
            wire:click="setType('income')"
            variant="{{ $type === 'income' ? 'primary' : 'outline' }}"
            class="w-full">
            💰 Income
        </flux:button>
    </div>

    {{-- Bank Account Selector --}}
    <div>
        <flux:label>Bank Account</flux:label>
        <flux:select wire:model="selectedAccount" placeholder="Select account..." class="mt-1">
            <flux:select.option value="Bank Account 1">Bank Account 1</flux:select.option>
            <flux:select.option value="Bank Account 2">Bank Account 2</flux:select.option>
        </flux:select>
        @error('selectedAccount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    {{-- Amount + Currency --}}
    <div>
        <flux:label>Amount</flux:label>
        <flux:input.group class="mt-1">
            <flux:select class="max-w-fit" wire:model="currency">
                <flux:select.option value="RON">RON</flux:select.option>
                <flux:select.option value="EUR">EUR</flux:select.option>
            </flux:select>
            <flux:input placeholder="0.00" type="number" wire:model="amount"/>
        </flux:input.group>
        @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    {{-- Category Picker --}}
    <div>
        <flux:label>Category</flux:label>
        <div class="grid grid-cols-4 gap-3 mt-2">
            @foreach($categories as $category)
                <div
                    wire:click="setCategory('{{ $category->value }}')"
                    class="flex flex-col items-center gap-1 cursor-pointer">
                    <flux:avatar
                        src="{{ asset('images/' . $category->image()) }}"
                        class="{{ $selectedCategory === $category->value ? 'ring-2 ring-blue-500' : '' }}"/>
                    <span
                        class="text-xs {{ $selectedCategory === $category->value ? 'text-blue-500 font-semibold' : 'text-gray-500' }}">
            {{ $category->value }}
        </span>
                </div>
            @endforeach
        </div>
        @error('selectedCategory') <span class="text-red-500 text-xs">Please select a category</span> @enderror
    </div>

    {{-- Date --}}
    <flux:input type="date" label="Date" wire:model="date"/>
    @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

    {{-- Description --}}
    <flux:field>
        <flux:label>Description <span class="text-gray-400 text-xs">(optional)</span></flux:label>
        <flux:input placeholder="What was this for?" wire:model="description"/>
    </flux:field>

    {{-- Submit --}}
    <flux:button wire:click="save" variant="primary" class="w-full">
        Save Transaction
    </flux:button>

</div>
