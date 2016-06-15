<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;

use JWTAuth;
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

    public function store(Request $request, $recipeId)
    {
        $this->validate($request, [
            'body' => 'required|min:6',
        ]);

        $recipe = Recipe::findOrFail($recipeId);
        $user = $user = JWTAuth::parseToken()->authenticate();

        $comment = $recipe->comments()->create([
            'recipe_id' => $recipeId,
            'user_id'   => $user->id,
            'title'     => $request->get('title', ''),
            'body'      => $request->body,
        ]);

        return response()->json($comment, 201);
    }
}
