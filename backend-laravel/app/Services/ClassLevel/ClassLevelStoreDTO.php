<?php

namespace App\Services\ClassLevel;

class ClassLevelStoreDTO
{
    /**
     * @var int|null
     */
    public ?int $id;

    /**
     * @var int
     */
    public int $niveau;

    /**
     * @var string
     */
    public string $label;

    /**
     * @param int|null $id
     * @param string $label
     * @param int $niveau
     */
    public function __construct(?int $id, string $label, int $niveau)
    {
        $this->id = $id;
        $this->label = $label;
        $this->niveau = $niveau;
    }
}
