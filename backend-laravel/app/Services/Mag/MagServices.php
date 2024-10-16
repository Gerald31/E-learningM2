<?php

namespace App\Services\Mag;

use App\Models\Mag;

class MagServices
{
    /**
     * @param MagStoreDTO $storeDTO
     * @return mixed
     */
    public function storeMag (MagStoreDTO $storeDTO)
    {
        return Mag::updateOrCreate(
            [
                'mag_id' => $storeDTO->id
            ],
            [
                'title'     => $storeDTO->title,
                'sub_title'     => $storeDTO->subTitle,
                'content'    => $storeDTO->content,
                'picture'    => $storeDTO->picture,
                'enable'    => $storeDTO->enable,
            ]
        );
    }

    /**
     * @param int $magId
     * @return mixed
     */
    public function updatePublishMag(int $magId)
    {
        $mag = Mag::find($magId);
        if ($mag) {
            $mag->enable = !$mag->enable;
            $mag->save();
        }
        return $mag;
    }

    /**
     * @return mixed
     */
    public function getAllMag() {
        return (new Mag)->getAllMags();
    }

    /**
     * @param int $magId
     * @return mixed
     */
    public function deleteMag(int $magId) {
        return Mag::find($magId)->delete();
    }
}
