<?php

namespace App\Services\Stripe;

class CreateCustomerDTO
{
    /**
     * @var string
     */
    public string $country;

    /**
     * @var string
     */
    public string $account_token;

    /**
     * @var string
     */
    public string $emailUser;

    /**
     * @var string
     */
    public string $tokenBank;

    /**
     * @var string|null
     */
    public ?string $type;

    public function __construct(string $country, string $account_token, string $emailUser, string $tokenBank, ?string $type = 'custom')
    {
        $this->country = $country;
        $this->account_token = $account_token;
        $this->emailUser = $emailUser;
        $this->tokenBank = $tokenBank;
        $this->type = $type;
    }
}
