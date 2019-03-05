<?php

namespace Kopokopo\SDK\Requests;

class SettleFundsRequest extends BaseRequest
{
    public function getAmount()
    {
        return $this->getRequestData('amount');
    }

    public function getCurrency()
    {
        return $this->getRequestData('currency');
    }

    public function getDestination()
    {
        if (!isset($this->data['destination'])) {
            return null;
        }

        return $this->getRequestData('destination');
    }

    public function getSettleFundsBody()
    {
        return [
            'amount' => [
                $this->getCurrency(),
                $this->getAmount(),
            ],
            'destination' => $this->getDestination(),
        ];
    }
}
