<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTutoratRequests;
use App\Models\Tutorat;
use App\Models\User;
use App\Services\Stripe\StripeServices;
use App\Services\Tutorat\EmailTutoratService;
use App\Services\Tutorat\StoreTutoratState;
use App\Services\Tutorat\TutoratServices;
use App\Services\Tutorat\TutoratStoreDTO;
use App\Services\Upload\UploadServices;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TutoratController extends Controller
{
    /**
     * @var TutoratServices
     */
    private TutoratServices $tutoratServices;

    /**
     * TutoratController constructor.
     * @param TutoratServices $tutoratServices
     */
    public function __construct(TutoratServices $tutoratServices)
    {
        $this->tutoratServices = $tutoratServices;
    }

    /**
     * @param StoreTutoratRequests $requests
     * @param UploadServices $uploadServices
     * @param EmailTutoratService $emailTutoratService
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function store(StoreTutoratRequests $requests, UploadServices $uploadServices, EmailTutoratService $emailTutoratService)
    {
        try {
            $tutorat = $this->tutoratServices->storeTutorat(new TutoratStoreDTO(
                $requests->tutorat_id,
                $requests->class_level_id,
                $requests->subject_id,
                $requests->prof_id,
                $requests->etudiant_id,
                $requests->description,
                $uploadServices->storeFile($requests->documents),
                $requests->subject,
                Carbon::createFromFormat('Y-m-d',$requests->date),
                Carbon::createFromFormat('H:i',$requests->time_start),
                Carbon::createFromFormat('H:i',$requests->time_end),
                floatval($requests->price),
            ));

            $urlToRegister = $requests->header('origin') ? "{$requests->header('origin')}/student/tutorat/{$tutorat->tutorat_id}/detail" : url("/student/tutorat/{$tutorat->tutorat_id}/detail");

            $emailTutoratService->sendEmailForNewTutorat($requests->class_level_id, $urlToRegister);

            $response = response($tutorat, Response::HTTP_OK);
        }
        catch (Exception $e){
            $error['message'] = $e->getMessage();
            $response = response($error, Response::HTTP_NOT_FOUND);
        }
        return $response;
    }

    /**
     * @param int $prof_id
     * @return Application|\Illuminate\Http\Response|ResponseFactory
     */
    public function getTutoratToProfesseur(int $prof_id)
    {
        $allTutoratProf = $this->tutoratServices->getTutoratToProfesseur($prof_id);
        return response($allTutoratProf, Response::HTTP_OK);
    }

    /**
     * @param int $etudiant_id
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function getTutoratToEtudiant(int $etudiant_id)
    {
      $allTutoratEtudiant = $this->tutoratServices->getTutoratToEtudiant($etudiant_id);
      return response($allTutoratEtudiant, Response::HTTP_OK);
    }

    /**
     * @param Tutorat $tutorat
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function affectedStudentTutorat(Tutorat $tutorat) {
        if ($tutorat) {
            $user = Auth::guard('api')->user();
            $affected = $this->tutoratServices->affectedStudentTutorat($tutorat, $user->id);
            return response($affected, Response::HTTP_OK);
        }
        return response(['success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Tutorat $tutorat
     * @param User $user
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function affectedTutorTutorat(Tutorat $tutorat, User $tutor) {
        if ($tutorat) {
            $affected = $this->tutoratServices->affectedTutorTutorat($tutorat, $tutor->id);
            return response($affected, Response::HTTP_OK);
        }
        return response(['success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param int $userId
     * @param string $role
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function getMyTutorat(int $userId, string $role) {
        $myTutorats = $this->tutoratServices->getMyTutorats($userId, $role);
        return response($myTutorats, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @param StoreTutoratRequests $request
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function update(int $id, StoreTutoratRequests $request)
    {
        try {
            $this->tutoratServices->storeTutorat(new TutoratStoreDTO(
                $id,
                $request->class_level_id,
                $request->subject_id,
                $request->prof_id,
                $request->etudiant_id,
                $request->description,
                $request->documents,
                $request->subject,
                Carbon::createFromFormat('Y-m-d',$request->date),
                Carbon::createFromFormat('H:i',$request->time_start),
                Carbon::createFromFormat('H:i',$request->time_end),
                floatval($request->price),
            ));

            $error['message'] = "Les données ont été mises à jour";
            $response = response($error, Response::HTTP_OK);
        } catch (Exception $e) {
            $error['message'] = $e->getMessage();
            $response = response($error, Response::HTTP_NOT_FOUND);
        }
        return $response;
    }

    /**
     * @param int $tutoratId
     * @return Application|\Illuminate\Http\Response|ResponseFactory
     */
    public function show(int $tutoratId) {
        $tutorat = $this->tutoratServices->getTutorat($tutoratId);
        return response($tutorat, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param StripeServices $stripeServices
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function updateTutoratState(Request $request, StripeServices $stripeServices)
    {
        try {
            $paymentIntentId = $request->paymentIntentId;
            $tutoratId = $request->tutoratId;
            $user = Auth::guard('api')->user();
            $reference = 'Facture_' . $tutoratId .'_'. date('Y_m_d_h_i_s');

            $paymentIntent = $stripeServices->getPaymentIntent($paymentIntentId);
            $invoice = $paymentIntent->charges->data && count($paymentIntent->charges->data) > 0 && current($paymentIntent->charges->data)->receipt_url ?? null;

            $tutorat = $this->tutoratServices->getTutorat($tutoratId);

            $storeTutoratState = new StoreTutoratState($tutoratId, $stripeServices->totalAmount($tutorat->price), $user->id, $invoice, $reference, true);
            $tutoratState = $this->tutoratServices->createTutoratState($storeTutoratState);
            $response = response([
                "success" => true,
                'tutorat_state_user' => $tutoratState
            ]);
        } catch (Exception $e) {
            $error['message'] = $e->getMessage();
            $response = response($error, Response::HTTP_NOT_FOUND);
        }
        return $response;
    }
}
