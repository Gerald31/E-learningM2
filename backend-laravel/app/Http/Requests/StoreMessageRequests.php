<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequests extends FormRequest
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
            'message' => 'nullable',
            'type' => 'required',
            'replyTo' => 'nullable',
            'attachment' => 'nullable|file|mimes:csv,txt,rar,zip,pdf,png,jpg,jpeg,gif,mp4,mkv|max:157286400',
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'attachment.mimes' => 'mimes:csv,txt,xlx,xls,pdf',
            'attachment.max' => 'max:157286400',
        ];
    }
}
