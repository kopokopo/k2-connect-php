<?php

namespace Kopokopo\SDK\Requests;

class MerchantWalletRequest extends BaseRequest
{
    public function getPhoneNumber()
    {
        return $this->getRequestData('phoneNumber');
    }

    public function getNetwork()
    {
        return $this->getRequestData('network');
    }

    public function getSettlementAccountBody()
    {
        return [
            'phone_number' => $this->getPhoneNumber(),
            'network' => $this->getNetwork(),
        ];
    }
}
