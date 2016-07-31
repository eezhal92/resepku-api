<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;

use Storage;
use JWTAuth;
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

        $perPage = request('limit', 20);

        dd(request()->header());

        return response()->json($recipes->paginate($perPage));
    }

    public function show($recipeId)
    {
        $recipe = Recipe::with('user', 'lovedBy', 'categories')->findOrFail($recipeId);

        return response()->json($recipe);
    }

    public function store(Requests\API\V1\RecipeRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $recipe = $user->recipes()->create([
            'title'     => $request->get('title'),
            'sub_title' => $request->get('sub_title'),
            'slug'      => str_slug($request->get('title')),
            'body'      => $request->get('body'),
        ]);

        $recipe->categories()->attach($request->get('categories'));
        $recipe->load('categories');

        return response()->json($recipe, 201);
    }

    public function update(Requests\API\V1\RecipeRequest $request, $recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);

        $recipe->update([
            'title'     => $request->get('title'),
            'sub_title' => $request->get('sub_title'),
            'slug'      => str_slug($request->get('title')),
            'body'      => $request->get('body'),
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

    public function postImage(Request $request, $recipeId)
    {
        $this->validate($request, [
            'image' => 'required|image:jpg,png',
        ]);

        $recipe = Recipe::findOrFail($recipeId);

        $image = $request->file('image');
        $path = "/img/recipes/{$recipe->id}.{$image->getClientOriginalExtension()}";

        Storage::disk('public')->put(
            $path,
            file_get_contents($image->getRealPath())
        );

        $recipe->image = $path;
        $recipe->save();

        return response()->json([
            'id' => $recipe->id,
            'image' => $recipe->image,
        ]);
    }

    public function loveRecipe(Request $request, $recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);

        $user = JWTAuth::parseToken()->authenticate();

        if (! $user->isLoveRecipe($recipe)) {
            $user->loves()->attach($recipe->id);

            return response()->json([
                'message' => "{$user->name} love {$recipe->title}",
            ]);
        }

        return response()->json([
            'message' => "{$user->name} already love {$recipe->title}",
        ]);
    }

    public function unLoveRecipe(Request $request, $recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);

        $user = JWTAuth::parseToken()->authenticate();

        if ($user->isLoveRecipe($recipe)) {
            $user->loves()->detach($recipe->id);
        }

        return response()->json([
            'message' => "{$user->name} don't love {$recipe->title}",
        ]);
    }
}
