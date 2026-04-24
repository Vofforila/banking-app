@php use App\Enums\CategoryIcon;use App\Models\UserCategory; @endphp
@props(['category', 'size' => 10])

@php
    $categoryName = is_array($category) ? ($category['name'] ?? '') : $category;

    // Look up from UserCategory in DB
    $userCategory = UserCategory::where('user_id', auth()->id())
        ->where('name', $categoryName)
        ->first();

    if ($userCategory) {
        $iconEnum = CategoryIcon::tryFrom($userCategory->icon ?? 'food');
        $imgPath  = $iconEnum ? $iconEnum->path() : CategoryIcon::Food->path();
        $color    = $userCategory->color ?? '#f97316';
    } else {
        // Fallback if category not found
        $imgPath = CategoryIcon::Food->path();
        $color   = '#f97316';
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
