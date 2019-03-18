<?php

namespace Kopokopo\SDK;

use GuzzleHttp\Client;

class K2
{
    // Mock server
    // For testing purposes
    const BASE_DOMAIN = 'https://9284bede-3488-4b2b-a1e8-d6e9f8d86aff.mock.pstmn.io';

    protected $clientId;
    protected $clientSecret;

    protected $client;
    protected $tokenClient;
    public $baseUrl;

    public function __construct($clientId, $clientSecret, $baseUrl)
    {
        $this->baseUrl = $baseUrl;

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
