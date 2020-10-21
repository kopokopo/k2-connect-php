<?php

namespace Kopokopo\SDK\Requests;

class PayRequest extends BaseRequest
{
    public function getDestinationRef()
    {
        return $this->getRequestData('destinationReference');
    }

    // TODO: Validate destination type
    public function getDestinationType()
    {
        return $this->getRequestData('destinationType');
    }

    public function getAmount()
    {
        return $this->getRequestData('amount');
    }

    public function getCurrency()
    {
        return $this->getRequestData('currency');
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
            'destination_reference' => $this->getDestinationRef(),
            'destination_type' => $this->getDestinationType(),
            'amount' => [
                'currency' => $this->getCurrency(),
                'value' => $this->getAmount(),
            ],
            'metadata' => $this->getMetadata(),
            '_links' => [
                'callback_url' => $this->getUrl(),
            ],
        ];
    }
}
