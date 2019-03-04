<?php

namespace Kopokopo\SDK\Requests;

abstract class BaseRequest
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getHeaders()
    {
        return array('Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$this->getRequestData('accessToken'),
                );
    }

    protected function getRequestData($key)
    {
        if (!isset($this->data[$key])) {
            throw new \InvalidArgumentException("You have to provide the $key");
        }

        return $this->data[$key];
    }
}
