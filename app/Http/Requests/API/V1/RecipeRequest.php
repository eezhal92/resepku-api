<?php

namespace App\Http\Requests\API\V1;

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

        return request()->user()->id == $recipe->user_id;
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
            'body'       => 'required|min:6',
            'categories' => 'required|array',
        ];

        return $rules;
    }
}
