<?php


namespace App\Service;

use Twilio\Rest\Client;
use Symfony\Component\DependencyInjection\Attribute\AsService; // Use AsService attribute

class TwilioService
{
    private $accountSid;
    private $authToken;
    private $fromNumber;

    public function __construct(string $accountSid, string $authToken, string $fromNumber)
    {
        $this->accountSid = $accountSid;
        $this->authToken = $authToken;
        $this->fromNumber = $fromNumber;
    }




    public function sendSMS(string $recipientNumber, string $messageBody): bool
    {
        $client = new Client($this->accountSid, $this->authToken);

        try {
            $message = $client->messages->create(
                $recipientNumber, // Recipient phone number
                array(
                    'from' => $this->fromNumber,  // Your Twilio phone number
                    'body' => $messageBody
                )
            );

            if ($message->status === 'sent') {
                return true;  // SMS sent successfully
            } else {
                // Handle errors (explained later)
                return false;
            }
        } catch (Exception $e) {
            // Handle exceptions (explained later)
            return false;
        }
    }
}
