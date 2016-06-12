<?php

namespace App\Http\Requests\API\V1;

use Gate;
use App\Recipe;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteRecipeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {   
        return Gate::allows('delete-recipe', Recipe::findOrFail($this->route('id')));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
