<?php

namespace App\Services\Email;

class RegisterEmail
{
    /**
     * @var SendinBlueEmailService
     */
    protected SendinBlueEmailService $blueEmailService;

    /**
     * @param SendinBlueEmailService $blueEmailService
     */
    public function __construct(SendinBlueEmailService $blueEmailService)
    {
        $this->blueEmailService = $blueEmailService;
    }

    /**
     * @param string $emailReceiver
     * @param string $htmlMailContent
     * @return bool
     */
    public function sendConfirmation(string $emailReceiver, string $htmlMailContent): bool
    {
        return $this->blueEmailService->send(
            'Validation compte it-Web-School',
            $htmlMailContent,
            'Confirmation de compte',
            $emailReceiver
        );
    }

}
