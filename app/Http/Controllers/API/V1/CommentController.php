<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;

use JWTAuth;
use App\Recipe;
use App\Comment;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Middleware\GetUserFromToken;

class CommentController extends Controller
{

    /**
     * @var \App\User
     */
    private $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->applyJsonAndJwtAuthMiddleware();
    }

    private function applyJsonAndJwtAuthMiddleware()
    {
        $this->middleware('json', [
            'only' => ['store', 'destroy'],
        ]);

        $this->middleware(GetUserFromToken::class, [
            'only' => ['store', 'destroy'],
        ]);
    }

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

        $comment = $recipe->comments()->create([
            'recipe_id' => $recipeId,
            'user_id'   => $this->user->id,
            'title'     => $request->get('title', ''),
            'body'      => $request->body,
        ]);

        return response()->json($comment, 201);
    }

    public function destroy($recipeId, $commentId)
    {
        $comment = Comment::where('recipe_id', $recipeId)
                         ->where('id', $commentId)
                         ->firstOrFail();

        if (! $this->user->can('delete-comment', $comment)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment has been deleted',
        ]);
    }
}
