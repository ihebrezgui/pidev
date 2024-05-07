<?php

// src/Service/MailService.php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class MailService
{
    private $client;
    private $apiKey;
    private $endpoint;

    public function __construct(HttpClientInterface $client, string $apiKey, string $endpoint)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->endpoint = $endpoint;
    }

    public function sendEmailWithPdf(string $subject, string $text, string $from, string $to, $pdfContent)
    {
        $response = $this->client->request('POST', $this->endpoint, [
            'auth_basic' => ['api', $this->apiKey],
            'headers' => [
                'Content-Type' => 'multipart/form-data',
            ],
            'body' => [
                'from' => $from,
                'to' => $to,
                'subject' => $subject,
                'text' => $text,
                'attachment' => [
                    'content' => base64_encode($pdfContent),
                    'filename' => 'enseignent-info.pdf',
                    'contentType' => 'application/pdf'
                ]
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to send email: ' . $response->getContent(false));
        }
    }
}
