<?php

namespace App\Services\Tutorat;

use App\Models\Tutorat;
use App\Models\TutoratState;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TutoratServices
{
    /**
     * @param TutoratStoreDTO $storeDTO
     * @return mixed
     */
    public function storeTutorat(TutoratStoreDTO $storeDTO) {
        return Tutorat::updateOrCreate(
            [
                'tutorat_id' => $storeDTO->tutorat_id
            ],
            [
            'class_level_id'                    => $storeDTO->class_level_id,
            'subject_id'                        => $storeDTO->subject_id,
            'prof_id'                           => $storeDTO->prof_id,
            'etudiant_id'                       => $storeDTO->etudiant_id,
            'description'                       => $storeDTO->description,
            'documents'                         => $storeDTO->documents,
            'subject'                           => $storeDTO->subject,
            'date'                              => $storeDTO->date,
            'time_start'                        => $storeDTO->time_start,
            'time_end'                          => $storeDTO->time_end,
            'price'                             => $storeDTO->price
        ]);
    }

    /**
     * @param int $profId
     * @return mixed
     */
    public function getTutoratToProfesseur(int $profId)
    {
        return Tutorat::where('prof_id', $profId)
        ->get();
    }

    /**
     * @param int $etudiantId
     * @return mixed
     */
    public function getTutoratToEtudiant(int $etudiantId)
    {
        return Tutorat::where('etudiant_id', $etudiantId)
        ->get();
    }

    /**
     * @param int $userId
     * @param string $role
     * @return Builder[]|Collection
     */
    public function getMyTutorats(int $userId, string $role) {
        $tutoratQuery = Tutorat::with(['classLevel', 'subjectClass', 'student', 'tutor', 'tutoratStates'])
            ->select([
                'tutorat_id',
                'tutorat.class_level_id',
                'tutorat.subject_id',
                'tutorat.prof_id',
                'tutorat.etudiant_id',
                'tutorat.description',
                'tutorat.subject',
                'tutorat.date',
                'tutorat.time_start',
                'tutorat.time_end',
                'tutorat.price',
                'tutorat.created_at',
                'tutorat.updated_at',
            ])
            ->where(function ($query) {
                $query->where("date", '>', Carbon::now()->format('Y-m-d'))
                    ->orWhere(function ($q) {
                        $q->where("date", '=', Carbon::now()->format('Y-m-d'))
                            ->where("time_start", '>=', Carbon::now()->format('H:i'));
                    });
            });

            if ($role === User::ROLE_ETUDIANT) {
                $tutoratQuery->join('user_skill', 'user_skill.class_level_id', '=', 'tutorat.class_level_id')
                    ->where('user_skill.user_id', $userId)
                    ->where(function($query) use ($userId) {
                        $query->whereNull('etudiant_id')
                            ->orWhere('etudiant_id', $userId);
                    });
            } else {
                $tutoratQuery->where('prof_id', $userId);
            }

        return $tutoratQuery->orderBy("date", 'ASC')
            ->orderBy("time_start", 'ASC')
            ->get();
    }

    /**
     * @param int $tutoratId
     * @return Builder|Builder|Collection|Model|null
     */
    public function getTutorat(int $tutoratId)
    {
        return Tutorat::with(['classLevel', 'subjectClass', 'student', 'tutor'])
            ->select([
                'tutorat_id',
                'tutorat.class_level_id',
                'tutorat.subject_id',
                'tutorat.prof_id',
                'tutorat.etudiant_id',
                'tutorat.description',
                'tutorat.subject',
                'tutorat.date',
                'tutorat.time_start',
                'tutorat.time_end',
                'tutorat.price',
                'tutorat.created_at',
                'tutorat.updated_at',
                'tutorat.documents',
            ])
            ->find($tutoratId);
    }

    /**
     * @param Tutorat $tutorat
     * @param int $studentId
     * @return bool
     */
    public function affectedStudentTutorat(Tutorat $tutorat, int $studentId) {
        return $tutorat->update([
            'etudiant_id' => $studentId
        ]);
    }

    /**
     * @param Tutorat $tutorat
     * @param int $tutorId
     * @return bool
     */
    public function affectedTutorTutorat(Tutorat $tutorat, int $tutorId) {
        return $tutorat->update([
            'prof_id' => $tutorId
        ]);
    }

    /**
     * @param StoreTutoratState $tutoratState
     * @return mixed
     */
    public function createTutoratState(StoreTutoratState $tutoratState)
    {
        return TutoratState::create(
            [
                'tutorat_id'    => $tutoratState->tutorat_id,
                'price'    => $tutoratState->price,
                'users_id'   => $tutoratState->user_id,
                'invoice'   => $tutoratState->invoice,
                'reference' => $tutoratState->reference,
                'affected'  => $tutoratState->affected,
            ]);
    }

    /**
     * @param int $tutoratId
     * @return array
     */
    public function getContactsTutorat(int $tutoratId): array
    {
        $students = TutoratState::join('users',  function ($join) {
            $join->on('tutorat_state_user.users_id', '=', 'users.id');
        })
            ->where('tutorat_state_user.tutorat_id', $tutoratId)
            ->select('users.*')
            ->orderBy('users.firstname', 'desc')
            ->get()->toArray();
        $tutor = Tutorat::join('users',  function ($join) {
            $join->on('tutorat.prof_id', '=', 'users.id');
        })
            ->select('users.*')->find($tutoratId)->toArray();

        array_push($students, $tutor);

        return $students;
    }

    public function tutoratAvailableDone()
    {
        $tutoratAvailable = [];
        $tutoratDone = [];
        $tutoratAvailables = (new Tutorat)->getTutoratQuery()
            ->where(function ($query) {
                $query->where("date", '>', Carbon::now()->format('Y-m-d'))
                    ->orWhere(function ($q) {
                        $q->where("date", '=', Carbon::now()->format('Y-m-d'))
                            ->where("time_start", '>=', Carbon::now()->format('H:i'));
                    });
            })
            ->withCount('tutoratStates')
            ->orderBy("date", 'ASC')
            ->orderBy("time_start", 'ASC')
            ->get();

        $tutoratDones = (new Tutorat)->getTutoratQuery()
            ->where(function ($query) {
                $query->where("date", '<', Carbon::now()->format('Y-m-d'))
                    ->orWhere(function ($q) {
                        $q->where("date", '=', Carbon::now()->format('Y-m-d'))
                            ->where("time_start", '<', Carbon::now()->format('H:i'));
                    });
            })
            ->orderBy("date", 'ASC')
            ->orderBy("time_start", 'ASC')
            ->get();

        foreach ($tutoratAvailables as $tutorat) {
            if ($tutorat->tutorat_states_count < 4) {
                $tutoratAvailable[] = $tutorat;
            } else {
                $tutoratDone[] = $tutorat;
            }
        }

        foreach ($tutoratDones as $tutorat) {
            $tutoratDone[] = $tutorat;
        }
        return [
            'tutoratProgress' => count($tutoratAvailable),
            'tutoratDone' => count($tutoratDone),
        ];
    }
}
