<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Services\Email\RegisterEmail;
use App\Services\Upload\UploadServices;
use App\Services\User\BankingStoreDTO;
use App\Services\User\SkillUserDTO;
use App\Services\User\UserServices;
use App\Services\User\UserStoreDTO;
use Exception;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends Controller
{

    /**
     * User registration API method
     *
     * @param StoreUser $request
     * @param UserServices $userServices
     * @param UploadServices $uploadServices
     * @param RegisterEmail $registerEmail
     * @return Response
     */
    public function register(StoreUser $request, UserServices $userServices, UploadServices $uploadServices, RegisterEmail $registerEmail): Response
    {
        $userSkills = $request->userSkill;
        $bankingInformation = $request->bankingInformation;
        try {
            $activationToken = Str::random(32);
            $avatar = null;
            if ($request->hasFile('avatar'))
            {
                $avatar = $uploadServices->storeFile($request->file('avatar'));
            }
            $user = $userServices->storeUser(new UserStoreDTO(
                $request->lastname,
                $request->firstname,
                $request->email,
                $request->password,
                $request->roles,
                $request->boolean('status'),
                $activationToken,
                $avatar,
                $request->city,
                $request->code_postal,
                PhoneNumber::make($request->phone, 'FR')->formatE164(),
                $request->address,
                $request->gender,
                $request->date_of_birth,
            ));

            if (!empty($userSkills) && count($userSkills) > 0) {
                foreach ($userSkills as $userSkill) {
                    $userServices->storeSkillUser(new SkillUserDTO($user->id, $userSkill['classLevel'], $userSkill['subject'] ?? null));
                }
            }

            if (isset($bankingInformation) && count($bankingInformation) > 0)
            {
                $ibanDocument = null;
                if ($bankingInformation->hasFile('ibanDocument'))
                {
                    $ibanDocument = $uploadServices->storeFile($bankingInformation->file('ibanDocument'));
                }
                $userServices->storeBanking(new BankingStoreDTO($user->id, $bankingInformation['iban'], $ibanDocument));
            }

            $urlValidation = $request->header('origin') ? "{$request->header('origin')}/auth/verify-email/{$activationToken}" : url("/auth/verify-email/{$activationToken}");

            $htmlMailContent = view("emails.confirm_account", [
                'url_validation' => $urlValidation,
            ])->render();

            $registerEmail->sendConfirmation($request->email, $htmlMailContent);

            $success['firstname']  = $user->firstname;
            $success['token'] = $user->createToken('accessToken')->accessToken;
            $response = response($success, Response::HTTP_OK);
        } catch (Exception $e) {
            $error['token'] = [];
            $error['message'] = $e->getMessage();
            $response = response($error, Response::HTTP_NOT_FOUND);
        }

        return $response;
    }
}
