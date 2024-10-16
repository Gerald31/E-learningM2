<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTutoratRequests extends FormRequest
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
            'class_level_id' => ['required', 'exists:class_level,class_level_id'],
            'subject_id' => ['required', 'exists:subject,subject_id'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:now'],
            'time_start' => 'required',
            'time_end' => 'required',
            'price' => ['required', 'numeric'],
            'subject' => 'required',
            'description' => 'required',
            'documents' => 'required|file|max:10240|mimes:csv,xlx,xls,pdf,doc,docx,jpeg,jpg,png'
        ];
    }

    public function messages()
    {
        return [
            'class_level_id.required' => 'La classe n\'est pas renseignée.',
            'subject_id.required' => 'La matiere n\'est pas renseignée.',
            'date.required' => 'La date de tutorat n\'est pas renseignée.',
            'date.date_format' => 'La date de tutorat doit être une date au format jj/mm/aaaa.',
            'date.after_or_equal' => 'La date de tutorat doit être antérieure ou égale à celle d\'aujourd\'hui.',
            'time_start.required' => 'L\'heure de debut de tutorat n\'est pas renseignée.',
            'time_end.required' => 'L\'heure du fin de tutorat n\'est pas renseignée.',
            'price.required' => 'Le tarif de tutorat n\'est pas renseignée.',
            'price.numeric' => 'Le tarif de tutorat doit être un nombre.',
            'subject.required' => 'L\'objet de tutorat n\'est pas renseignée.',
            'description.required' => 'La description de tutorat n\'est pas renseignée.',
            'documents.required' => 'Le document de tutorat n\'est pas renseignée.',
            'documents.mimes' => 'Le document de tutorat doit être un type de csv,xlx,xls,pdf,doc,docx,jpeg,png.',
            'documents.max' => 'La taille du document doit être antérieure ou égale 10M.',
        ];
    }
}
