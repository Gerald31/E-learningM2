<?php


namespace App\Services\User;


class BankingStoreDTO
{
    /**
     * @var int
     */
    public int $userId;

    /**
     * @var string
     */
    public string $iban;

    /**
     * @var string|null
     */
    public ?string $ibanFile;

    /**
     * @param int $userId
     * @param string $iban
     * @param string|null $ibanFile
     */
    public function __construct(int $userId, string $iban, ?string $ibanFile)
    {
        $this->userId = $userId;
        $this->iban = $iban;
        $this->ibanFile = $ibanFile;
    }
}
