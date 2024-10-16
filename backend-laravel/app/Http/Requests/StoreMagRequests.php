<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMagRequests extends FormRequest
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
            'title'    => 'required',
            'contenu'   => 'required',
            'picture'   => 'required'
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'title.required' => 'Le titre d\'un article n\'est pas renseigné',
            'contenu.required' => 'Le contenu d\'un article n\'est pas renseigné',
            'picture.required' => 'L\'image d\'un article n\'est pas renseigné',
        ];
    }
}
