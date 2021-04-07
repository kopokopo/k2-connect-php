<?php

namespace Kopokopo\SDK\Requests;

class SettleFundsRequest extends BaseRequest
{
    public function getAmount()
    {
        if (!isset($this->data['amount'])) {
            return null;
        }

        return $this->getRequestData('amount');
    }

    public function getCurrency()
    {
        if (!isset($this->data['currency'])) {
            return null;
        }
        return $this->getRequestData('currency');
    }

    public function getDestinationRef()
    {
        if (!isset($this->data['destinationReference'])) {
            return null;
        }

        return $this->getRequestData('destinationReference');
    }

    public function getDestinationType()
    {
        if (!isset($this->data['destinationType'])) {
            return null;
        }

        return $this->getRequestData('destinationType');
    }

    public function getUrl()
    {
        return $this->getRequestData('callbackUrl');
    }

    public function getSettleFundsBody()
    {
        return [
            'amount' => [
                'currency' => $this->getCurrency(),
                'value' => $this->getAmount(),
            ],
            'destination_reference' => $this->getDestinationRef(),
            'destination_type' => $this->getDestinationType(),
            '_links' => [
                'callback_url' => $this->getUrl(),
            ],
        ];
    }
}
