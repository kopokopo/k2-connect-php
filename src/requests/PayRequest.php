<?php

namespace Kopokopo\SDK\Requests;

class PayRequest extends BaseRequest
{
    public function getDestination()
    {
        return $this->getRequestData('destination');
    }

    public function getAmount()
    {
        return $this->getRequestData('amount');
    }

    public function getUrl()
    {
        return $this->getRequestData('callbackUrl');
    }

    public function getMetadata()
    {
        if (!isset($this->data['metadata'])) {
            return null;
        }

        return $this->getRequestData('metadata');
    }

    public function getPayBody()
    {
        return [
            'destination' => $this->getDestination(),
            'amount' => $this->getAmount(),
            'metadata' => $this->getMetadata(),
            '_links' => [
                'callback_url' => $this->getUrl(),
            ],
        ];
    }
}
