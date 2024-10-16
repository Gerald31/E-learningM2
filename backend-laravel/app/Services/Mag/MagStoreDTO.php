<?php

namespace App\Services\Mag;

class MagStoreDTO
{
    /**
     * @var int|null
     */
    public ?int $id;

    /**
     * @var string
     */
    public string $title;

    /**
     * @var string
     */
    public string $subTitle;

    /**
     * @var string
     */
    public string $content;

    /**
     * @var string
     */
    public string $picture;

    /**
     * @var bool
     */
    public ?bool $enable;

    /**
     * @param int|null $id
     * @param string $title
     * @param string $subTitle
     * @param string $content
     * @param string $picture
     * @param bool|null $enable
     */
    public function __construct(
        ?int $id,
        string $title,
        string $subTitle,
        string $content,
        string $picture,
        ?bool $enable

    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->subTitle = $subTitle;
        $this->content = $content;
        $this->picture = $picture;
        $this->enable = $enable;
    }
}
