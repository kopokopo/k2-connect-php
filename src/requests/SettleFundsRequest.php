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

    public function getMetadata()
    {
        if (!isset($this->data['metadata'])) {
            return null;
        }

        return $this->getRequestData('metadata');
    }

    public function getSettleFundsBody()
    {
        return [
            'amount' => [
                $this->getCurrency(),
                $this->getAmount(),
            ],
            'destination_reference' => $this->getDestinationRef(),
            'destination_type' => $this->getDestinationType(),
            'metadata' => $this->getMetadata(),
        ];
    }
}
