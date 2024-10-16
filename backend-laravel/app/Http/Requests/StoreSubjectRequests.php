<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequests extends FormRequest
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
            'subjectName' => 'required',
            'classLevelId' => ['required', 'numeric','exists:class_level,class_level_id']
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'subjectName.required'        => 'Ce champ matiere est obligatoire.',
            'classLevelId.required'     => 'Ce champ classe id est obligatoire.',
            'classLevelId.numeric'    => 'L\'Id classe doit etre en valeur numerique',
            'classLevelId.exists'          => 'L\'Id classe n\'est pas existe dans la classe Id.'
        ];
    }
}
