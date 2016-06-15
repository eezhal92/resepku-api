<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username',
    ];

    protected $appends = [
        'recipe_count'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Recipe relation.
     *
     * @return HasManyRelation
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'love');
    }


    /**
     * User love recipe relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function loves()
    {
        return $this->belongsToMany(Recipe::class, 'love');
    }

    /**
     * Recipe count virtual attribute.
     *
     * @return HasManyRelation
     */
    public function getRecipeCountAttribute()
    {
        return $this->recipes()->count();
    }

    /**
     * Recipe count virtual attribute.
     *
     * @return HasManyRelation
     */
    public function getLovesAttribute()
    {
        return $this->loves()->get();
    }
}

