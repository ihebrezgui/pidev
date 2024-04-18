<?php
// src/Service/MessageGenerator.php
namespace App\Service;

use Twilio\Rest\Client;

class SmsGenerator
{
    
    public function SendSms(string $number, string $name, string $text)
    {
        
        $accountSid = $_ENV['ACa5878e87279f325c8ba5a43ff21c5a69'];  //Identifiant du compte twilio
        $authToken = $_ENV['f4ac5d0dfdf266c6f1881056a8e2ec3e']; //Token d'authentification
        $fromNumber = $_ENV['+15076985148']; // Numéro de test d'envoie sms offert par twilio

        $toNumber = $number; // Le numéro de la personne qui reçoit le message
        $message = ''.$name.' vous a envoyé le message suivant:'.' '.$text.''; //Contruction du sms

        //Client Twilio pour la création et l'envoie du sms
        $client = new Client($accountSid, $authToken);

        $client->messages->create(
            $toNumber,
            [
                'from' => $fromNumber,
                'body' => $message,
            ]
        );


    }
}
