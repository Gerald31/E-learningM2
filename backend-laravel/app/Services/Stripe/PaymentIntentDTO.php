<?php


namespace App\Services\Stripe;


class PaymentIntentDTO
{
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
     * @var string|null
     */
    public ?string $accountCustomerDest;

    /**
     * @var string|null
     */
    public ?string $currency;

    /**
     * @param string $description
     * @param float $priceTotal
     * @param float $amountTransfer
     * @param string|null $accountCustomerDest
     * @param string|null $currency
     */
    public function __construct(
        string $description,
        float $priceTotal,
        float $amountTransfer,
        ?string $accountCustomerDest,
        ?string $currency = "EUR"
    ) {
        $this->description = $description;
        $this->priceTotal = $priceTotal;
        $this->amountTransfer = $amountTransfer;
        $this->accountCustomerDest = $accountCustomerDest;
        $this->currency = $currency;
    }
}
