<?php

namespace App\Services\User;

class SkillUserDTO
{
    /**
     * @var int
     */
    public int $userId;

    /**
     * @var int|null
     */
    public ?int $subjectId;

    /**
     * @var int
     */
    public int $classLevelId;

    /**
     * @param int $userId
     * @param int $classLevelId
     * @param int|null $subjectId
     */
    public function __construct(int $userId, int $classLevelId, ?int $subjectId)
    {
        $this->userId = $userId;
        $this->subjectId = $subjectId;
        $this->classLevelId = $classLevelId;
    }

}
