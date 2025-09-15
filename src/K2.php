<?php

namespace Kopokopo\SDK;

use GuzzleHttp\Client;
use Kopokopo\SDK\Requests\K2InitialiseRequest;

class K2
{
    protected $options;

    protected $client;
    protected $tokenClient;
    public $baseUrl;

    public function __construct($options)
    {
        $k2InitialiseRequest = new K2InitialiseRequest($options);

        $this->baseUrl = $k2InitialiseRequest->getBaseUrl();
        $this->options = $k2InitialiseRequest->getOptions();

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
        $token = new TokenService($this->tokenClient, $this->options);

        return $token;
    }

    public function Webhooks()
    {
        $webhooks = new Webhooks($this->client, $this->options);

        return $webhooks;
    }

    public function StkService()
    {
        $stk = new StkService($this->client, $this->options);

        return $stk;
    }

    public function PayService()
    {
        $pay = new PayService($this->client, $this->options);

        return $pay;
    }

    public function SettlementTransferService()
    {
        $transfer = new SettlementTransferService($this->client, $this->options);

        return $transfer;
    }

    public function PollingService()
    {
        $poll = new PollingService($this->client, $this->options);

        return $poll;
    }

    public function SmsNotificationService()
    {
        $smsNotify = new SmsNotificationService($this->client, $this->options);

        return $smsNotify;
    }
}
