@php use App\Enums\CategoryIcon;use App\Enums\TransactionCategory; @endphp
@props(['category', 'size' => 10])

@php
    $predefined = null;
    try {
        $predefined = TransactionCategory::from($category['name'] ?? $category);
    } catch (ValueError $e) {}

    if ($predefined) {
        $imgPath = $predefined->icon()->path();
        $color   = $predefined->color();
    } else {
        $iconEnum = CategoryIcon::tryFrom($category['icon'] ?? 'shopping');
        $imgPath  = $iconEnum ? $iconEnum->path() : CategoryIcon::Food->path();
        $color    = $category['color'] ?? '#3b82f6';
    }
@endphp

<div
    class="rounded-full flex items-center justify-center flex-shrink-0"
    style="width: {{ $size * 4 }}px; height: {{ $size * 4 }}px; background-color: {{ $color }}20; border: 2px solid {{ $color }}40;">
    <img
        src="{{ $imgPath }}"
        style="width: {{ $size * 2 }}px; height: {{ $size * 2 }}px; filter: drop-shadow(0 0 0 {{ $color }});"
        alt="{{ $category['name'] ?? $category }}"
    />
</div>
