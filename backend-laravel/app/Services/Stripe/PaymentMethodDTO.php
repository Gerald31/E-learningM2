<?php

namespace App\Services\Stripe;

class PaymentMethodDTO
{
    /**
     * @var string
     */
    public string $numberCard;

    /**
     * @var int
     */
    public int $expMonth;

    /**
     * @var int
     */
    public int $expYear;

    /**
     * @var string
     */
    public string $cvc;

    /**
     * @param string $numberCard
     * @param int $expMonth
     * @param int $expYear
     * @param string $cvc
     */
    public function __construct(
        string $numberCard,
        int $expMonth,
        int $expYear,
        string $cvc
    ) {
        $this->numberCard = $numberCard;
        $this->expMonth = $expMonth;
        $this->expYear = $expYear;
        $this->cvc = $cvc;
    }
}
