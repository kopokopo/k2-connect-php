<?php

namespace Kopokopo\SDK;

use GuzzleHttp\Client;
use Kopokopo\SDK\Requests\K2InitialiseRequest;


use Symfony\Component\Dotenv\Dotenv;


include_once("../vendor/autoload.php");
class K2
{
    protected $options;

    protected $client;
    protected $tokenClient;
    public $baseUrl;

    /**
     * Define env method similar to laravel's
     *
     * @param String $env_param | Environment Param Name
     *
     * @return String
     */
    public static function env(string $env_param): string
    {

        $dotenv = new Dotenv();

        $dotenv->load('../.env');

        $env = getenv($env_param);

        return $env;
    }


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
}
