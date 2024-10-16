<?php

namespace App\Services\Stripe;

class PaymentDTO
{
    /**
     * @var string
     */
    public string $paymentMethodId;

    /**
     * @var string
     */
    public string $description;

    /**
     * @var float
     */
    public float $priceTotal;

    /**
     * @var float
     */
    public float $amountTransfer;

    /**
     * @var string
     */
    public string $accountCustomerDest;

    /**
     * @var string|null
     */
    public ?string $currency;

    /**
     * @param string $paymentMethodId
     * @param string $description
     * @param float $priceTotal
     * @param float $amountTransfer
     * @param string $accountCustomerDest
     * @param string|null $currency
     */
    public function __construct(
        string $paymentMethodId,
        string $description,
        float $priceTotal,
        float $amountTransfer,
        string $accountCustomerDest,
        ?string $currency = "eur"
    ) {
        $this->paymentMethodId = $paymentMethodId;
        $this->description = $description;
        $this->priceTotal = $priceTotal;
        $this->amountTransfer = $amountTransfer;
        $this->accountCustomerDest = $accountCustomerDest;
        $this->currency = $currency;
    }
}
