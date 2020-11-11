<?php

namespace Kopokopo\SDK;

abstract class Service
{
    protected $client;
    protected $clientId;
    protected $clientSecret;

    public function __construct($client, $options)
    {
        $this->client = $client;
        $this->clientId = $options['clientId'];
        $this->clientSecret = $options['clientSecret'];
        $this->apiKey = $options['apiKey'];
    }

    protected static function error($data)
    {
        return [
            'status' => 'error',
            'data' => $data,
        ];
    }

    protected static function postSuccess($data)
    {
        return [
            'status' => 'success',
            'location' => $data->getHeaders()['location'],
        ];
    }

    protected static function tokenSuccess($data)
    {
        return [
            'status' => 'success',
            'data' => json_decode($data->getBody()->getContents()),
        ];
    }

    protected static function success($data)
    {
        return [
            'status' => 'success',
            'data' => $data,
        ];
    }
}
