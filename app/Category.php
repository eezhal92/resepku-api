<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];

    /**
     * Recipe relation.
     *
     * @return BelongsToManyRelation
     */
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }
}
