<div class="space-y-6">

    {{-- Type Toggle --}}
    <div class="flex gap-2">
        <flux:button wire:click="setType('expenses')" variant="{{ $type === 'expenses' ? 'primary' : 'outline' }}"
                     size="sm">
            💸 Expenses
        </flux:button>
        <flux:button wire:click="setType('income')" variant="{{ $type === 'income' ? 'primary' : 'outline' }}"
                     size="sm">
            💰 Income
        </flux:button>
    </div>

    {{-- Predefined Categories --}}
    <div>
        <p class="text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-3">Default Categories</p>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($this->getPredefinedCategories() as $category)
                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center gap-3 mb-2">
                        <img src="{{ asset('images/' . $category->image()) }}"
                             class="w-8 h-8 rounded-full object-cover"/>
                        <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $category->value }}</p>
                    </div>
                    <div class="flex flex-wrap gap-1">
                        @foreach($category->keywords() as $keyword)
                            <span
                                class="text-xs bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300 rounded-full px-2 py-0.5">
                                {{ $keyword }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- User Defined Categories --}}
    <div>
        <p class="text-xs font-semibold tracking-widest uppercase text-zinc-400 mb-3">Your Categories</p>

        @if($userCategories->count() === 0)
            <p class="text-zinc-400 text-sm">No custom categories yet. Add one using the button above.</p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($userCategories as $category)
                    <div class="bg-white dark:bg-zinc-800 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $category->name }}</p>
                            <div class="flex gap-1">
                                <flux:button
                                    wire:click="openEdit({{ $category->id }})"
                                    size="sm"
                                    variant="outline"
                                    icon="pencil"/>
                                <flux:button
                                    wire:click="delete({{ $category->id }})"
                                    wire:confirm="Delete this category?"
                                    size="sm"
                                    variant="danger"
                                    icon="trash"/>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-1">
                            @foreach($category->keywords as $keyword)
                                <span
                                    class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full px-2 py-0.5">
                                    {{ $keyword }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Edit Modal --}}
    @if($showEditModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-zinc-800 rounded-2xl p-6 w-full max-w-md shadow-xl space-y-4">

                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Edit Category</h2>

                <flux:field>
                    <flux:label>Category Name</flux:label>
                    <flux:input wire:model="editName"/>
                    @error('editName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Type</flux:label>
                    <flux:select wire:model="editType">
                        <flux:select.option value="expenses">Expenses</flux:select.option>
                        <flux:select.option value="income">Income</flux:select.option>
                    </flux:select>
                </flux:field>

                <flux:field>
                    <flux:label>Keywords <span class="text-zinc-400 text-xs">(comma separated)</span></flux:label>
                    <flux:input wire:model="editKeywords"/>
                    @error('editKeywords') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </flux:field>

                <div class="flex gap-3 pt-2">
                    <flux:button wire:click="saveEdit" variant="primary" class="flex-1">Save</flux:button>
                    <flux:button wire:click="$set('showEditModal', false)" variant="outline" class="flex-1">Cancel
                    </flux:button>
                </div>

            </div>
        </div>
    @endif

</div>
