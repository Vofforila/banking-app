<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    protected $fillable = ['user_id', 'name', 'type', 'keywords', 'icon', 'color'];

    protected $casts = [
        'keywords' => 'array',
    ];

    public static function detect(string $payer, string $description = '', ?int $userId = null): string
    {
        if (!$userId) return 'Uncategorized';

        $text = strtolower($payer . ' ' . $description);

        $userCategories = self::where('user_id', $userId)->get();

        foreach ($userCategories as $category) {
            foreach ($category->keywords as $keyword) {
                if (str_contains($text, strtolower($keyword))) {
                    return $category->name;
                }
            }
        }

        return 'Uncategorized';
    }

//    public function user(): BelongsTo
//    {
//        return $this->belongsTo(User::class);
//    }
}
