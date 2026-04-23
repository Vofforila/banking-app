<x-layouts::app.sidebar :title="'Categories'">
    <flux:main>
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="xl">Categories</flux:heading>
            <livewire:add-category/>
        </div>
        <livewire:categories/>
    </flux:main>
</x-layouts::app.sidebar>
