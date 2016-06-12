<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;

use App\User;
use App\Recipe;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RecipeController extends Controller
{
    public function index()
    {
        $queryStrings = request()->intersect('categories', 'user_id');

        $recipes = Recipe::with('user', 'categories');

        if (request()->has('categories')) {
            $categories = explode(',', $queryStrings['categories']);

            $recipes->ofCategories($categories);
        }

        if (request()->has('user_id')) {
            $recipes->ofUser($queryStrings['user_id']);
        }

        return response()->json($recipes->paginate(20));
    }

    public function show($recipeId)
    {
        $recipe = Recipe::with('user', 'categories')->findOrFail($recipeId);

        return response()->json($recipe);
    }

    public function store(Requests\API\V1\RecipeRequest $request)
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

    public function update(Requests\API\V1\RecipeRequest $request, $recipeId)
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

    public function destroy(Requests\API\V1\DeleteRecipeRequest $request, $recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);

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
