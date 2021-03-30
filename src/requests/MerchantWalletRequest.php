<?php

namespace Kopokopo\SDK\Requests;

class MerchantWalletRequest extends BaseRequest
{
    public function getFirstName()
    {
        return $this->getRequestData('firstName');
    }

    public function getLastName()
    {
        return $this->getRequestData('lastName');
    }

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
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'phone_number' => $this->getPhoneNumber(),
            'network' => $this->getNetwork(),
        ];
    }
}
