<?php

namespace Kopokopo\SDK\Requests;

class PayRecipientMerchantRequest extends BaseRequest
{
    public function getType()
    {
        return $this->getRequestData('type');
    }

    public function getAliasName()
    {
        return $this->getRequestData('aliasName');
    }

    public function getTillNumber()
    {
        return $this->getRequestData('tillNumber');
    }

    public function getPayRecipientBody()
    {
        return [
            'type' => $this->getType(),
            'pay_recipient' => [
                'alias_name' => $this->getAliasName(),
                'till_number' => $this->getTillNumber(),
            ],
        ];
    }
}
