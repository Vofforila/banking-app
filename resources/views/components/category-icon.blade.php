@php use App\Enums\CategoryIcon;use App\Enums\TransactionCategory; @endphp
@props(['category', 'size' => 10])

@php
    // Handle both string and array input
    $categoryName = is_array($category) ? ($category['name'] ?? '') : $category;

    $predefined = null;
    try {
        $predefined = TransactionCategory::from($categoryName);
    } catch (ValueError $e) {}

    if ($predefined) {
        $imgPath = $predefined->icon()->path();
        $color   = $predefined->color();
    } else {
        $iconEnum = CategoryIcon::tryFrom($category['icon'] ?? 'food');
        $imgPath  = $iconEnum ? $iconEnum->path() : CategoryIcon::Food->path();
        }
@endphp

<div
    class="rounded-full flex items-center justify-center flex-shrink-0"
    style="width: {{ $size * 4 }}px; height: {{ $size * 4 }}px; background-color: {{ $color }}20; border: 2px solid {{ $color }}40;">
    <img
        src="{{ $imgPath }}"
        style="width: {{ $size * 2 }}px; height: {{ $size * 2 }}px;"
        alt="{{ $categoryName }}"
    />
</div>
