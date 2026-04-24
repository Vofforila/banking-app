<?php

namespace App\Services;

use App\Enums\TransactionCategory;
use App\Models\User;
use App\Models\UserCategory;

class CategorySeederService
{
    public function seedDefaultCategories(User $user): void
    {
        foreach (TransactionCategory::cases() as $category) {
            UserCategory::create([
                'user_id' => $user->id,
                'name' => $category->value,
                'type' => $category->type(),
                'icon' => $category->icon(),
                'color' => $category->color(),
                'keywords' => $category->keywords(),
            ]);
        }
    }
}
