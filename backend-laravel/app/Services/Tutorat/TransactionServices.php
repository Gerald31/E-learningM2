<?php


namespace App\Services\Tutorat;


use App\Models\TutoratState;

class TransactionServices
{
    /**
     * @return mixed
     */
    public function lastTransactions()
    {
       return TutoratState::with(['tutorat', 'etudiant'])->orderBy('created_at', 'DESC')->limit(5)->get();
    }

    public function groupUser() {
        return TutoratState::with(['tutorat'])->select(
            DB::raw('count(id) as `data`'),
            DB::raw("DATE_FORMAT(created_at, '%m-%Y') as new_date"),
            DB::raw("YEAR(created_at) year"),
            DB::raw("MONTH(created_at) month"),
        )->groupBy('new_date', 'year', 'month')
            ->orderBy('year', 'ASC')
            ->orderBy('month', 'ASC')
            ->get();
    }

}
