<?php

namespace Kopokopo\SDK;

abstract class Service
{
    protected $client;
    protected $clientId;
    protected $clientSecret;

    public function __construct($client, $clientId, $clientSecret)
    {
        $this->client = $client;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    protected static function error($data)
    {
        return [
            'status' => 'error',
            'data' => $data,
        ];
    }

    protected static function success($data)
    {
        return [
            'status' => 'success',
            'data' => json_decode($data->getBody()->getContents()),
        ];
    }

    protected static function webhookSuccess($data)
    {
        return [
            'status' => 'success',
            'data' => $data,
        ];
    }
}
