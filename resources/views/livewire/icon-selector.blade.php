<div class="space-y-3">
    {{-- Color Picker --}}
    <p class="text-xs text-zinc-400 uppercase tracking-widest">Background Color</p>
    <div class="flex flex-wrap gap-2">
        @foreach($colors as $color)
            <button
                type="button"
                wire:click="selectColor('{{ $color }}')"
                class="w-7 h-7 rounded-full border-2 transition-all
                    {{ $selectedColor === $color ? 'border-zinc-900 dark:border-white scale-110' : 'border-transparent' }}"
                style="background-color: {{ $color }}">
            </button>
        @endforeach
    </div>

    {{-- Icon Grid --}}
    <p class="text-xs text-zinc-400 uppercase tracking-widest">Icon</p>
    <div class="grid grid-cols-6 gap-2">
        @foreach($icons as $icon)
            <button
                type="button"
                wire:click="selectIcon('{{ $icon->value }}')"
                class="flex items-center justify-center p-2 rounded-xl border-2 transition-all
                {{ $selectedIcon === $icon->value ? 'border-blue-500' : 'border-transparent hover:border-zinc-300' }}"
                title="{{ $icon->label() }}">

                <div
                    class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                    style="background-color: {{ $selectedColor }}20; border: 2px solid {{ $selectedColor }}40;">
                    <img src="{{ $icon->path() }}" class="w-5 h-5" alt="{{ $icon->label() }}"/>
                </div>
            </button>
        @endforeach
    </div>
</div>
