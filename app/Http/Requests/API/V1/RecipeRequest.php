<?php

namespace App\Http\Requests\API\V1;

use JWTAuth;
use App\Recipe;
use App\Http\Requests\Request;

class RecipeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (request()->method() == 'POST') {
            return true;
        }

        $recipe = Recipe::findOrFail($this->route('id'));

        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return $user->id == $recipe->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title'      => 'required|min:6',
            'sub_title'  => 'required|min:6',
            'body'       => 'required|min:6',
            'categories' => 'required|array',
        ];

        return $rules;
    }
}
