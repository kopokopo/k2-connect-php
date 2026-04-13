<?php

namespace Kopokopo\SDK\Requests;

class ExternalPaybillRequest extends BaseRequest
{
    public function getType()
    {
        return $this->getRequestData('type');
    }

    public function getPaybillName()
    {
        return $this->getRequestData('paybillName');
    }

    public function getPaybillNumber()
    {
        return $this->getRequestData('paybillNumber');
    }

    public function getPaybillAccountNumber()
    {
        return $this->getRequestData('paybillAccountNumber');
    }

    public function getExternalRecipientBody()
    {
        return [
            'type' => $this->getType(),
            'external_recipient' => [
                'paybill_name' => $this->getPaybillName(),
                'paybill_number' => $this->getPaybillNumber(),
                'paybill_account_number' => $this->getPaybillAccountNumber(),
            ],
        ];
    }
}
