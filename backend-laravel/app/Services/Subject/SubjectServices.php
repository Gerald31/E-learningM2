<?php

namespace App\Services\Subject;

use App\Models\Subject;

class SubjectServices
{

    /**
     * @param SubjectStoreDTO $subjectStoreDTO
     * @return mixed
     */
    public function storeSubject(SubjectStoreDTO $storeDTO)
    {
        return Subject::updateOrCreate(
            [
                'subject_id' => $storeDTO->id
            ],
            [
                'subject_name'     => $storeDTO->subjectName,
                'class_level_id'    => $storeDTO->classLevelId,
            ]
        );
    }

    /**
     * @return mixed
     */
    public function getAllSubject() {
        return (new Subject)->getAll();
}
}
