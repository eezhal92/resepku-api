<?php

namespace App;

use JWTAuth;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['title', 'sub_title', 'slug', 'body', 'image'];

    protected $appends = ['love', 'loved_by_me'];

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

    /**
     * Comments relation.
     *
     * @return HasManyRelation
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

     /**
     * Recipe loved by user relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lovedBy()
    {
        return $this->belongsToMany(User::class, 'love');
    }

    /**
     * Query recipe for certain categories.
     *
     * @return QueryBuilder
     */
    public function scopeOfCategories($query, $categoryIds)
    {
        return $query->join('category_recipe', 'recipes.id', '=', 'category_recipe.recipe_id')
                     ->join('categories', 'category_recipe.category_id', '=', 'categories.id')
                     ->select('recipes.*')
                     ->groupBy('recipes.id')
                     ->whereIn('categories.id', $categoryIds);
    }

    /**
     * Query recipe for spesific user.
     *
     * @return QueryBuilder
     */
    public function scopeOfUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function getLoveAttribute()
    {
        return $this->lovedBy()->count();
    }

    public function getLovedByMeAttribute()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $lovingUsers = $this->lovedBy()->get();
            $idsOfUsersLoveThisRecipe = $lovingUsers->map(function ($user) {
                return $user->id;
            })->toArray();

            return in_array($user->id, $idsOfUsersLoveThisRecipe);
        } catch (\Exception $e) {

        }

        return false;
    }
}
