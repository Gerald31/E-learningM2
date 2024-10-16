<?php

namespace App\Services\User;

use App\Models\BankingInformation;
use App\Models\ClassLevel;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserSkill;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserServices
{
    /**
     * @param UserStoreDTO $storeDTO
     * @return mixed
     */
    public function storeUser(UserStoreDTO $storeDTO) {
        return User::create([
            'firstname'         => $storeDTO->firstname,
            'lastname'          => $storeDTO->lastname,
            'email'             => $storeDTO->email,
            'password'          => $storeDTO->password,
            'roles'             => $storeDTO->roles,
            'status'            => $storeDTO->status,
            'activation_token'  => $storeDTO->activation_token,
            'avatar'            => $storeDTO->avatar,
            'city'              => $storeDTO->city,
            'code_postal'       => $storeDTO->code_postal,
            'phone'             => $storeDTO->phone,
            'address'           => $storeDTO->address,
            'sexe'              => $storeDTO->sexe,
            'date_of_birth'              => $storeDTO->date_of_birth
        ]);
    }

    /**
     * @return User[]|Collection
     */
    public function getAllUsers() {
        return User::all();
    }

    /**
     * @param SkillUserDTO $skillUserDTO
     * @return mixed
     */
    public function storeSkillUser(SkillUserDTO $skillUserDTO) {
        return UserSkill::create([
            'user_id' => $skillUserDTO->userId,
            'subject_id' => $skillUserDTO->subjectId,
            'class_level_id' => $skillUserDTO->classLevelId,
        ]);
    }

    /**
     * @param BankingStoreDTO $bankingStoreDTO
     * @return mixed
     */
    public function storeBanking(BankingStoreDTO $bankingStoreDTO)
    {
        return BankingInformation::create([
            'user_id' => $bankingStoreDTO->userId,
            'iban' => $bankingStoreDTO->iban,
            'document' => $bankingStoreDTO->ibanFile,
        ]);
    }

    /**
     * @param int $classLevel
     * @return mixed
     */
    public function getEtudiantByClassLevel(int $classLevel) {
        return User::join('user_skill', function ($join) use ($classLevel) {
                $join->on('users.id', '=', 'user_skill.user_id')
                    ->where('user_skill.class_level_id', $classLevel);
            })
            ->select('id', 'firstname', 'lastname', 'email')
            ->where('roles', User::ROLE_ETUDIANT)
            ->distinct('id')
            ->get();
    }

    /**
     * @param int $classLevel
     * @param int $subjectId
     * @return mixed
     */
    public function getProfesseurByClassLevel(int $classLevel, int $subjectId) {
        return User::join('user_skill', function ($join) use ($classLevel, $subjectId) {
                $join->on('users.id', '=', 'user_skill.user_id')
                    ->where('user_skill.class_level_id', $classLevel)
                    ->where('user_skill.subject_id', $subjectId);
        })
            ->select('id', 'firstname', 'lastname', 'email')
            ->where('roles', User::ROLE_PROF)
            ->distinct('id')
            ->get();
    }

    /**
     * @param int $userId
     * @param string $role
     * @return array
     */
    public function getMySkill(int $userId, string $role): array
    {
        $classLevels = ClassLevel::join('user_skill', function ($join) use ($userId) {
            $join->on('class_level.class_level_id', '=', 'user_skill.class_level_id')
                ->where('user_skill.user_id', $userId);
        });

        if ($role === User::ROLE_PROF) {
            $subjects = Subject::join('user_skill', function ($join) use ($userId) {
                $join->on('subject.subject_id', '=', 'user_skill.subject_id')
                    ->where('user_skill.user_id', $userId);
            })->get();
        } else {
            $userSkillIds = $classLevels->pluck('class_level.class_level_id');
            $subjects = Subject::whereIn('class_level_id', $userSkillIds)->get();
        }

        return [
            'classLevels' => $classLevels->get(),
            'subjects' => $subjects,
        ];
    }

    /**
     * @param string $token
     * @return mixed
     */
    public function verifyToken(string $token)
    {
        $user = User::with('bankingInformation')->where('activation_token', $token)->first();
        if ($user) {
            $user->status = true;
            $user->activation_token = null;
            $user->save();
        }
        return $user;
    }

    /**
     * @param string $role
     * @return mixed
     */
    public function getUsersByRole(string $role) {
        return User::where('roles', $role)
            ->orderBy('firstname', 'ASC')
            ->orderBy('lastname', 'ASC')
            ->get();
    }

   /**
     * @return mixed
     */
    public function countUser() {
        return User::count();
    }

    /**
     * @param string $role
     * @return mixed
     */
    public function countUserByRole(string $role) {
        return User::where('roles', $role)->count();
    }

    /**
     * @return mixed
     */
    public function groupUser() {
        return User::select(
            DB::raw('count(id) as `data`'),
            DB::raw("DATE_FORMAT(created_at, '%m-%Y') as new_date"),
            DB::raw("YEAR(created_at) year"),
            DB::raw("MONTH(created_at) month"),
        )->groupBy('new_date', 'year', 'month')
            ->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->get();
    }

    /**
     * @return mixed
     */
    public function recentUsers()
    {
        return User::orderBy('created_at', 'DESC')->limit(5)->get();
    }
}
