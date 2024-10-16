<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\User\UserServices;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * @param int $id
     * @param UserServices $userServices
     * @return mixed
     */
    public function getEtudiantByClassLevel(int $id, UserServices $userServices) {
        return $userServices->getEtudiantByClassLevel($id);
    }

    /**
     * @param int $classId
     * @param int $subjectId
     * @param UserServices $userServices
     * @return mixed
     */
    public function getProfesseurByClassLevel(int $classId, int $subjectId, UserServices $userServices) {
        return $userServices->getProfesseurByClassLevel($classId, $subjectId);
    }

    /**
     * @param int $userId
     * @param string $role
     * @param UserServices $userServices
     * @return array
     */
    public function getMySkill(int $userId, string $role, UserServices $userServices): array
    {
        return $userServices->getMySkill($userId, $role);
    }

    /**
     * @param string $role
     * @param UserServices $userServices
     * @return mixed
     */
    public function usersByRole(string $role, UserServices $userServices) {
        return $userServices->getUsersByRole($role);
    }
}


