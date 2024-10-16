<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassLevelRequests extends FormRequest
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
            'label'    => 'required',
            'niveau'   => 'required|numeric'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'label.required' => 'Ce champ nom du classe est obligatoire.',
            'niveau.required' => 'Ce champ niveau est obligatoire.',
            'niveau.numeric' => 'Le niveau doit etre en valeur numerique',
        ];
    }
}
