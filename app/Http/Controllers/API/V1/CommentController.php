<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;

use App\Recipe;
use App\Comment;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index($recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);

        return $recipe->comments()->paginate(10);
    }
}
