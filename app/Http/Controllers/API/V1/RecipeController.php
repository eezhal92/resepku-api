<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;

use App\User;
use App\Recipe;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RecipeController extends Controller
{
    public function index()
    {
        if (request()->has('categories')) {
            $categories = explode(',', request()->get('categories'));

            $recipes = Recipe::ofCategories($categories)->paginate(20);
        } else {
            $recipes = Recipe::paginate(10);
        }

        return response()->json($recipes);
    }

    public function indexByUser($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        if (request()->has('categories')) {
            $categories = explode(',', request()->get('categories'));

            $recipes = $user->recipes()->ofCategories($categories)->get();
        } else {
            $recipes = $user->recipes()->paginate(10);
        }
        
        return response()->json($recipes);
    }

    public function show($username, $recipeId)
    {
        $recipe = Recipe::with('user', 'categories')->findOrFail($recipeId);

        return response()->json($recipe);
    }

    public function store(Requests\API\V1\RecipeRequest $request, $username)
    {
        $recipe = $request->user()->recipes()->create([
            'title' => $request->get('title'),
            'slug' => str_slug($request->get('title')),
            'body' => $request->get('body'),            
        ]);

        $recipe->categories()->attach($request->get('categories'));
        $recipe->load('categories');

        return response()->json($recipe, 201);
    }

    public function update(Requests\API\V1\RecipeRequest $request, $username, $recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);
        $recipe->update([
            'title' => $request->get('title'),
            'slug' => str_slug($request->get('title')),
            'body' => $request->get('body'),            
        ]);

        $recipe->categories()->sync($request->get('categories'));
        $recipe->load('categories');

        return response()->json($recipe, 200);
    }

    public function destroy($username, $recipeId)
    {
        try {
            $user = User::where('username', $username)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => "User with username `{$username}` not found"
            ], 404);
        }

        try {
            $recipe = Recipe::findOrFail($recipeId);
            if ($user->id !== $recipe->user_id) {
                throw new Exception;
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => "Recipe with id `{$recipeId}` not found"],
            404);
        } catch (Exception $e) {
            return response()->json([
                'message' => "Recipe with id `{$recipeId}` not related to User with username `{$username}`"],
            404);
        }
        
        $this->authorize('delete-recipe', $recipe);
        
        $recipe->categories()->delete();

        if ($recipe->delete()) {
            return response()->json([
                'message' => "Recipe with id {$recipeId} successfully been deleted"
            ]);
        }

        return response()->json([
            'message' => "Cannot delete recipe with id `{$recipeId}`"
        ], 400);
    }
}
