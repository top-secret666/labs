<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryObserver
{
    public function creating(Category $category): void
    {
        if (empty($category->slug)) {
            $category->slug = Str::slug($category->name);
        }
    }
}
