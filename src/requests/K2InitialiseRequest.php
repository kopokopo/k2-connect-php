<?php

namespace Kopokopo\SDK\Requests;

class K2InitialiseRequest extends BaseRequest
{
    public function getBaseUrl()
    {
        return $this->getRequestData('baseUrl');
    }

    public function getClientId()
    {
        return $this->getRequestData('clientId');
    }

    public function getClientSecret()
    {
        return $this->getRequestData('clientSecret');
    }

    public function getApiKey()
    {
        return $this->getRequestData('apiKey');
    }

    public function getOptions()
    {
        return [
            'baseUrl' => $this->getBaseUrl(),
            'clientId' => $this->getClientId(),
            'clientSecret' => $this->getClientSecret(),
            'apiKey' => $this->getApiKey(),
        ];
    }
}
