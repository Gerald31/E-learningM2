<?php

namespace App\Services\Email;

use Exception;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;

class SendinblueWrapper
{

    /**
     * @var TransactionalEmailsApi
     */
    protected TransactionalEmailsApi $apiMail;


    /**
     * @var SendSmtpEmail
     */
    protected SendSmtpEmail $sendSmtpEmail;


    /**
     * Donnée à envoyé au client
     *
     * @var array
     */
    protected array $mailData;


    /**
     * Class constructor.
     *
     * @param array $mailData Liste des options possibles lors de l'envoi de mail.
     *    $mailData = [
     *      'sender'       => (\SendinBlue\Client\Model\SendSmtpEmailSender),
     *      'to'           => (\SendinBlue\Client\Model\SendSmtpEmailTo[]) List of email addresses and names (optional) of the recipients,
     *      'bcc'          => (\SendinBlue\Client\Model\SendSmtpEmailBcc[]) List of email addresses and names (optional) of the recipients in bcc,
     *      'cc'           => (\SendinBlue\Client\Model\SendSmtpEmailCc[]) List of email addresses and names (optional) of the recipients in cc,
     *      'htmlContent' => (string) HTML body of the message ( Mandatory if 'templateId' is not passed, ignored if 'templateId' is passed ),
     *      'textContent' => (string) Plain Text body of the message ( Ignored if 'templateId' is passed ),
     *      'subject' => (string) Subject of the message. Mandatory if 'templateId' is not passed,
     *      'replyTo' => (\SendinBlue\Client\Model\SendSmtpEmailReplyTo) \SendinBlue\Client\Model\SendSmtpEmailReplyTo,
     *      'attachment' => (\SendinBlue\Client\Model\SendSmtpEmailAttachment[]),
     *      'headers' => 'object',
     *      'templateId' => 'int',
     *      'params' => 'object',
     *      'messageVersions' => '\SendinBlue\Client\Model\SendSmtpEmailMessageVersions[]',
     *      'tags' => 'string[]'
     *    ]
     */
    public function __construct(array $mailData)
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('mail.sendinblue.api_key'));
        $this->apiMail = new TransactionalEmailsApi(null, $config);
        $this->sendSmtpEmail = new SendSmtpEmail($mailData);
        $this->mailData = $mailData;
    }

    /**
     *
     * Envoi un mail via l'api v3 de Sendinblue
     *
     * @return string du message envoyé par sendinblue
     *
     * @throws Exception
     */
    public function sendEmail(): string
    {
        try {
            $result = $this->apiMail->sendTransacEmail($this->sendSmtpEmail);
            return $result->getMessageId();
        } catch (Exception $e) {
            report($e);
            throw new Exception("Erreur lors de l'envoi du mail via SendinBlue : ".$e->getMessage());
        }
    }
}

