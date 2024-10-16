<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Hash;

class UserStoreDTO
{
    /**
     * @var string
     */
    public string $email;

    /**
     * @var string
     */
    public string $password;

    /**
     * @var string
     */
    public string $firstname;

    /**
     * @var string
     */
    public string $lastname;

    /**
     * @var bool
     */
    public bool $status;

    /**
     * @var string
     */
    public string $roles;

    /**
     * @var string|null
     */
    public ?string $activation_token;

    /**
     * @var string|null
     */
    public ?string $avatar;

    /**
     * @var string|null
     */
    public ?string $city;

    /**
     * @var string|null
     */
    public ?string $code_postal;

    /**
     * @var string|null
     */
    public ?string $phone;

    /**
     * @var string|null
     */
    public ?string $address;

    /**
     * @var string|null
     */
    public ?string $sexe;

    /**
     * @var string|null
     */
    public ?string $date_of_birth;

    /**
     * @param string $lastname
     * @param string $firstname
     * @param string $email
     * @param string $password
     * @param string $roles
     * @param bool $status
     * @param string|null $activation_token
     * @param string|null $avatar
     * @param string|null $city
     * @param string|null $code_postal
     * @param string|null $phone
     * @param string|null $address
     * @param string|null $sexe
     * @param string|null $date_of_birth
     */
    public function __construct(
        string $lastname,
        string $firstname,
        string $email,
        string $password,
        string $roles,
        ?bool $status,
        ?string $activation_token,
        ?string $avatar,
        ?string $city,
        ?string $code_postal,
        ?string $phone,
        ?string $address,
        ?string $sexe,
        ?string $date_of_birth
    ) {
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->email = $email;
        $this->password = Hash::make($password);
        $this->status = $status;
        $this->roles = $roles;
        $this->address = $address;
        $this->sexe = $sexe;
        $this->code_postal = $code_postal;
        $this->avatar = $avatar;
        $this->city = $city;
        $this->activation_token = $activation_token;
        $this->phone = $phone;
        $this->date_of_birth = $date_of_birth;
    }
}
