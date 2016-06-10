<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
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
}
