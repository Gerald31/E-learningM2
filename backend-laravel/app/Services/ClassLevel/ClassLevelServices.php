<?php

namespace App\Services\ClassLevel;

use App\Models\ClassLevel;

class ClassLevelServices
{
    /**
     * @param ClassLevelStoreDTO $storeDTO
     * @return mixed
     */

    public function storeClassLevel(ClassLevelStoreDTO $storeDTO)
    {
        return ClassLevel::updateOrCreate(
            [
                'class_level_id' => $storeDTO->id
            ],
            [
                'label'     => $storeDTO->label,
                'niveau'    => $storeDTO->niveau,
            ]
        );
    }

    /**
     * @return mixed
     */
    public function getAllClassLevel() {
        return (new ClassLevel)->getAll();
    }
}
