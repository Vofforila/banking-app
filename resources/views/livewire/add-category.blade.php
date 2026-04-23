<div>
    {{-- Trigger Button --}}
    <flux:button wire:click="$set('showModal', true)" variant="outline" size="sm">
        ➕ Add Category
    </flux:button>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-zinc-800 rounded-2xl p-6 w-full max-w-md shadow-xl space-y-4">

                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Add Category</h2>

                {{-- Name --}}
                <flux:field>
                    <flux:label>Category Name</flux:label>
                    <flux:input wire:model="name" placeholder="e.g. Food, Transport..."/>
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </flux:field>

                {{-- Type --}}
                <flux:field>
                    <flux:label>Type</flux:label>
                    <flux:select wire:model="type">
                        <flux:select.option value="expenses">Expenses</flux:select.option>
                        <flux:select.option value="income">Income</flux:select.option>
                    </flux:select>
                </flux:field>

                {{-- Icon Selector --}}
                <flux:field>
                    <flux:label>Icon & Color</flux:label>
                    <livewire:icon-selector :key="'icon-selector'"/>
                </flux:field>

                {{-- Keywords --}}
                <flux:field>
                    <flux:label>Keywords <span class="text-zinc-400 text-xs">(comma separated)</span></flux:label>
                    <flux:input wire:model="keywordsInput" placeholder="Bolt, Glovo, KFC..."/>
                    @error('keywordsInput') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </flux:field>

                {{-- Existing user categories --}}
                @if($categories->count() > 0)
                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                        <p class="text-xs text-zinc-400 uppercase tracking-widest mb-2">Your Categories</p>
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                            @foreach($categories as $category)
                                <div
                                    class="flex items-center justify-between bg-zinc-50 dark:bg-zinc-700 rounded-lg px-3 py-2">
                                    <div>
                                        <span
                                            class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $category->name }}</span>
                                        <span
                                            class="text-xs text-zinc-400 ml-2">{{ implode(', ', $category->keywords) }}</span>
                                    </div>
                                    <span
                                        class="text-xs {{ $category->type === 'expenses' ? 'text-red-400' : 'text-green-400' }}">
                                        {{ ucfirst($category->type) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="flex gap-3 pt-2">
                    <flux:button wire:click="save" variant="primary" class="flex-1">Save</flux:button>
                    <flux:button wire:click="$set('showModal', false)" variant="outline" class="flex-1">Cancel
                    </flux:button>
                </div>

            </div>
        </div>
    @endif
</div>
