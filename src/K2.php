<?php

namespace Kopokopo\SDK;

use GuzzleHttp\Client;

class K2
{
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
        $this->version = 'v1/';

        $this->client = new Client([
            'base_uri' => $this->baseUrl . "/api/" . $this->version,
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

    public function SettlementTransferService()
    {
        $transfer = new SettlementTransferService($this->client, $this->clientId, $this->clientSecret);

        return $transfer;
    }
}
