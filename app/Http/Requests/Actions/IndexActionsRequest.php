<?php

namespace App\Http\Requests\Actions;

use Illuminate\Foundation\Http\FormRequest;

class IndexActionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page'     => 'sometimes|required|integer',
            'size'     => 'sometimes|required|integer',
            'sortBy'   => 'sometimes|required|string',
            'sortDesc' => 'sometimes|required|string'
        ];
    }
}