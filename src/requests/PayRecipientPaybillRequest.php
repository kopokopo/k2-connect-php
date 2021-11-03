<?php

namespace Kopokopo\SDK\Requests;

class PayRecipientPaybillRequest extends BaseRequest
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

    public function getPayRecipientBody()
    {
        return [
            'type' => $this->getType(),
            'pay_recipient' => [
                'paybill_name' => $this->getPaybillName(),
                'paybill_number' => $this->getPaybillNumber(),
                'paybill_account_number' => $this->getPaybillAccountNumber(),
            ],
        ];
    }
}
