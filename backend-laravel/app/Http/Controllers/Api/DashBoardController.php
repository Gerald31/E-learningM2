<?php


namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Services\Tutorat\TransactionServices;
use App\Services\Tutorat\TutoratServices;
use App\Services\User\UserServices;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class DashBoardController
{
    /**
     * @param UserServices $userServices
     * @param TransactionServices $transactionServices
     * @return Application|ResponseFactory|Response
     */
    public function statisticUserAdmin(UserServices $userServices, TransactionServices $transactionServices)
    {
        $lastUsers = $userServices->recentUsers();
        $lastestTransaction = $transactionServices->lastTransactions();

        return response([
            'total' => $userServices->countUser(),
            'usersGroup' => $userServices->groupUser(),
            'lastUsers' => $lastUsers,
            'lastestTransaction' => $lastestTransaction
        ]);
    }

    /**
     * @param UserServices $userServices
     * @param TutoratServices $tutoratServices
     * @return Application|ResponseFactory|Response
     */
    public function home(UserServices $userServices, TutoratServices $tutoratServices) {
        $statistics = [];
        $statistics['tutors'] = $userServices->countUserByRole(User::ROLE_PROF);
        $statistics['students'] = $userServices->countUserByRole(User::ROLE_ETUDIANT);
        $tutorat = $tutoratServices->tutoratAvailableDone();
        $statistics = array_merge($statistics, $tutorat);
        return response([
            'statistics' => $statistics,
        ]);
    }

}
