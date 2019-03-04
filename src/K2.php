<?php

namespace Kopokopo\SDK;

use GuzzleHttp\Client;

class K2
{
    // Mock server
    // For testing purposes
    const BASE_DOMAIN = 'https://7a060151-ec31-478c-bc6b-cfa5868e4380.mock.pstmn.io';

    protected $clientId;
    protected $clientSecret;

    protected $client;
    protected $tokenClient;
    public $baseUrl;

    public function __construct($clientId, $clientSecret)
    {
        $this->baseUrl = self::BASE_DOMAIN;

        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
        ]);

        $this->tokenClient = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function TokenService()
    {
        $token = new TokenService($this->tokenClient, $this->clientId, $this->clientSecret);

        return $token;
    }

    public function Webhooks()
    {
        $webhooks = new Webhooks($this->client, $this->clientId, $this->clientSecret);

        return $webhooks;
    }

    public function StkService()
    {
        $stk = new StkService($this->client, $this->clientId, $this->clientSecret);

        return $stk;
    }

    public function PayService()
    {
        $pay = new PayService($this->client, $this->clientId, $this->clientSecret);

        return $pay;
    }

    public function TransferService()
    {
        $transfer = new TransferService($this->client, $this->clientId, $this->clientSecret);

        return $transfer;
    }
}
