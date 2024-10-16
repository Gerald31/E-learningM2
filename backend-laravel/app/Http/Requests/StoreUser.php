<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
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
            'firstname'     => 'required',
            'lastname'      => 'required',
            'address'       => 'required',
            'city'          => 'required',
            'code_postal'   => 'required|regex:/^[0-9]{3,5}$/',
            'phone'         => ['required', Rule::phone()->detect()->country('FR')],
            'gender'        => 'required',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:8|confirmed',
            'password_confirmation'      => 'required',
            'roles'         => 'required',
            'userSkill.*.classLevel'         => 'required',
            'userSkill.*.subject'         => 'required_if:roles,=,'.User::ROLE_PROF,
            'date_of_birth'         => 'required|date|before:-13 years',
            'bankingInformation'         => 'required_if:roles,=,'.User::ROLE_PROF.'|array',
            'bankingInformation.iban'         => 'required_if:roles,=,'.User::ROLE_PROF.'|iban',
            'bankingInformation.ibanDocument'         => 'nullable|file|mimes:png,jpeg,jpg,pdf|max:5242880',
            'avatar' => 'required|file|mimes:png,jpeg,jpg|max:5242880'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'firstname.required'    => 'Votre nom ne peut pas être vide.',
            'lastname.required'    => 'Votre prénom ne peut pas être vide.',
            'address.required'    => 'Votre adresse ne peut pas être vide.',
            'city.required'    => 'Votre ville ne peut pas être vide.',
            'code_postal.required'    => 'Votre code postale ne peut pas être vide.',
            'code_postal.regex'    => 'Le format de votre code postale est invalide.',
            'phone.required'    => 'Votre numéro téléphone ne peut pas être vide.',
            'phone.phone'    => 'Le format de votre numéro téléphone est invalide.',
            'gender.required'    => 'Votre sexe ne peut pas être vide.',
            'email.required'        => 'Votre adresse email ne peut pas être vide.',
            'email.email'           => 'L\'adresse ' . $this->email . ' doit être une adresse mail valide.',
            'email.unique'          => 'Cet e-mail a déjà été utilisé.',
            'password.required'     => 'Votre mot de passe ne peut pas être vide.',
            'password.min'          => 'Le Mot de passe doit contenir 6 caractères.',
            'password.confirmed'          => 'La confirmation de mot de passe ne correspond pas.',
            'password_confirmation.required'     => 'Votre confirmation de mot de passe ne peut pas être vide.',
            'roles.required'    => 'Votre rôle ne peut pas être vide.',
            'userSkill.*.classLevel.required'    => 'Votre classe ne peut pas être vide.',
            'userSkill.*.subject.required_if'    => 'Votre matière ne peut pas être vide.',
            'date_of_birth.required'    => 'Votre date de naissance ne peut pas être vide.',
            'date_of_birth.before'    => 'Vous n’êtes pas supérieur de 13 ans.',
            'bankingInformation.iban.required_if'    => 'Votre IBAN ne peut pas être vide.',
            'bankingInformation.iban.iban'    => 'L\'IBAN est invalide.',
            'bankingInformation.ibanDocument.mimes'    => 'Le format document IBAN non valide(png,jpeg,jpg,pdf).',
            'bankingInformation.ibanDocument.max'    => 'La taille de document IBAN dépasse la limite maximale(5Mo).',
            'avatar.required'    => 'La photo de profil ne peut pas être vide.',
            'avatar.mimes'    => 'La format de photo de profil non valide(png,jpeg,jpg).',
            'avatar.max'    => 'La taille de photo de profil dépasse la limite maximale(5Mo).'
        ];
    }
}
