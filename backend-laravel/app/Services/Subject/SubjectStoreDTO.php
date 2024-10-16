<?php

namespace App\Services\Subject;

class SubjectStoreDTO
{
    /**
     * @var int|null
     */
    public ?int $id;

    /**
     * @var string
     */
    public string $subjectName;

    /**
     * @var int
     */
    public int $classLevelId;

    /**
     * @param int|null $id
     * @param string $subjectName
     * @param int $classLevelId
     */
    public function __construct( ?int $id, string $subjectName, int $classLevelId)
    {
        $this->id = $id;
        $this->subjectName = $subjectName;
        $this->classLevelId = $classLevelId;
    }
}
