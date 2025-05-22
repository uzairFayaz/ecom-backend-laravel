<?php

namespace App\Helpers;

use App\Models\Category;

class CategoryHelper
{
    public static function getCategoryOptions($categories = null, $prefix = '', $level = 0)
    {
        $options = [];
        
        if ($categories === null) {
            $categories = Category::with('childrenRecursive')
                ->whereNull('parent_cat_id')
                ->where('status', 'active')
                ->get();
        }

        foreach ($categories as $category) {
            $options[$category->id] = $prefix . $category->category_name;
            if ($category->childrenRecursive->isNotEmpty()) {
                $childOptions = self::getCategoryOptions(
                    $category->childrenRecursive,
                    $prefix . $category->category_name . ' > ',
                    $level + 1
                );
                $options = array_merge($options, $childOptions);
            }
        }

        return $options;
    }
}