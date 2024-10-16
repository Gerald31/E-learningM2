<?php


namespace App\Services\Tutorat;


use App\Services\Email\SendinBlueEmailService;
use App\Services\User\UserServices;

class EmailTutoratService
{
    /**
     * @var SendinBlueEmailService
     */
    protected SendinBlueEmailService $blueEmailService;
    protected UserServices $userServices;

    /**
     * @param SendinBlueEmailService $blueEmailService
     * @param UserServices $userServices
     */
    public function __construct(SendinBlueEmailService $blueEmailService, UserServices $userServices)
    {
        $this->blueEmailService = $blueEmailService;
        $this->userServices = $userServices;
    }

    /**
     * @param int $classLevelId
     * @param string $urlToRegister
     * @return void
     */
    public function sendEmailForNewTutorat(int $classLevelId, string $urlToRegister): void
    {
        $htmlMailContent = view("emails.new_tutorat_notification", [
            'url_to_register' => $urlToRegister,
        ])->render();

        $emailReceivers = $this->userServices->getEtudiantByClassLevel($classLevelId);

        foreach ($emailReceivers as $emailReceiver) {
            $this->blueEmailService->send(
                'Nouveau Tutorat',
                $htmlMailContent,
                'Nouveau Tutorat disponible',
                $emailReceiver->email
            );
        }
    }

}
