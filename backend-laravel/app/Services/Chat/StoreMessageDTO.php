<?php


namespace App\Services\Chat;


class StoreMessageDTO
{
    /**
     * @var int
     */
    public int $tutoratId;

    /**
     * @var int
     */
    public int $fromId;

    /**
     * @var string|null
     */
    public ?string $body;

    /**
     * @var string|null
     */
    public ?string $attachment;

    /**
     * @var int|null
     */
    public ?int $replyTo;

    /**
     * @var string|null
     */
    public ?string $type;

    /**
     * StoreMessageDTO constructor.
     * @param int $tutoratId
     * @param int $fromId
     * @param string|null $body
     * @param string|null $attachment
     * @param int|null $replyTo
     * @param string|null $type
     */
    public function __construct(
        int $tutoratId,
        int $fromId,
        ?string $body,
        ?string $attachment,
        ?int $replyTo,
        ?string $type
    ) {
        $this->tutoratId = $tutoratId;
        $this->fromId = $fromId;
        $this->body = $body;
        $this->attachment = $attachment;
        $this->replyTo = $replyTo;
        $this->type = $type;
    }
}
