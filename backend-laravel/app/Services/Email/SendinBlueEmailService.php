<?php

namespace App\Services\Email;

class SendinBlueEmailService
{
    /**
     * @param string $emailSubject
     * @param string|null $emailContents
     * @param string $emailTag
     * @return array
     */
    private function getEmailsParameters(string $emailSubject, ?string $emailContents, string $emailTag): array
    {
        return [
            'sender' => [
                'email' => config("mailers.emails.sender")
            ],
            'subject' => $emailSubject,
            'htmlContent' => $emailContents,
            'tags' => [
                $emailTag
            ]
        ];
    }

    /**
     * @param string $emailSubject
     * @param string|null $emailContents
     * @param string $emailTag
     * @param string|null $emailReceiver
     * @return bool
     */
    public function send(string $emailSubject, ?string $emailContents, string $emailTag, ?string $emailReceiver = null): bool
    {
        $emailParameters = $this->getEmailsParameters($emailSubject, $emailContents, $emailTag);

        $emailsAddress = [
            'to' => [
                [
                    'email' => $emailReceiver
                ]
            ]
        ];
        $emailConfigs = array_merge($emailsAddress, $emailParameters);

        if (config('app.env') !== 'local') {
            $sendinblueWrapper = new SendinblueWrapper($emailConfigs);
            try {
                return $sendinblueWrapper->sendEmail();
            } catch (\Throwable $th) {
                return false;
            }
        }
        return true;
    }
}
