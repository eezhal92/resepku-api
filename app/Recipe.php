<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['title', 'slug', 'body'];

    /**
     * User relation.
     *
     * @return HasManyRelation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Category relation.
     *
     * @return BelongsToManyRelation
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopeOfCategories($query, $categoryIds)
    {
        return $query->with('categories')
                     ->join('category_recipe', 'recipes.id', '=', 'category_recipe.recipe_id')
                     ->join('categories', 'category_recipe.category_id', '=', 'categories.id')
                     ->select('recipes.*')
                     ->groupBy('recipes.id')
                     ->whereIn('categories.id', $categoryIds);
    }
}
