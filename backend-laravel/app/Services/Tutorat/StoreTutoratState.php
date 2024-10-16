<?php

namespace App\Services\Tutorat;

class StoreTutoratState
{
    /**
     * @var int
     */
    public int $tutorat_id;

    /**
     * @var float
     */
    public float $price;
    /**
     * @var int
     */
    public int $user_id;
    /**
     * @var string|null
     */
    public ?string $invoice;
    /**
     * @var bool|null
     */
    public ?bool $affected;
    /**
     * @var string|null
     */
    public ?string $reference;

    /**
     * @param int $tutorat_id
     * @param int $user_id
     * @param string|null $invoice
     * @param string|null $reference
     * @param bool|null $affected
     */
    public function __construct(
        int $tutorat_id,
        float $price,
        int $user_id,
        ?string $invoice,
        ?string $reference,
        ?bool $affected = false
    )
    {
        $this->tutorat_id = $tutorat_id;
        $this->price = $price;
        $this->user_id = $user_id;
        $this->affected = $affected;
        $this->invoice = $invoice;
        $this->reference = $reference;
    }
}
