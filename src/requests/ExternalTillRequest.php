<?php

namespace Kopokopo\SDK\Requests;

class ExternalTillRequest extends BaseRequest
{
    public function getType()
    {
        return $this->getRequestData('type');
    }

    public function getTillName()
    {
        return $this->getRequestData('tillName');
    }

    public function getTillNumber()
    {
        return $this->getRequestData('tillNumber');
    }

    public function getExternalRecipientBody()
    {
        return [
            'type' => $this->getType(),
            'pay_recipient' => [
                'till_name' => $this->getTillName(),
                'till_number' => $this->getTillNumber(),
            ],
        ];
    }
}
