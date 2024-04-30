<?php
namespace App\Service;

use Twilio\Rest\Client;

class SmsSender
{
    private $client;
    private $from;

    public function __construct(string $sid, string $token, string $from)
    {
        $this->client = new Client($sid, $token);
        $this->from = $from;
    }

    public function sendSms(string $to, string $message): void
    {
        $this->client->messages->create(
            $to,
            [
                'from' => $this->from,
                'body' => $message,
            ]
        );
    }
}