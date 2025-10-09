<?php

namespace Kopokopo\SDK\Requests;

abstract class BaseRequest
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getAccessToken(): string
    {
        return $this->getRequestData('accessToken');
    }

    public function getHeaders(): array
    {
        return array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->getAccessToken(),
            'User-Agent' => 'Kopokopo-PHP-SDK',
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
