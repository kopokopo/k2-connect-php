<?php

namespace Kopokopo\SDK\Requests;

class PayRecipientMobileRequest extends BaseRequest
{
    public function getType()
    {
        return $this->getRequestData('type');
    }

    public function getFirstName()
    {
        return $this->getRequestData('firstName');
    }

    public function getLastName()
    {
        return $this->getRequestData('lastName');
    }

    public function getPhone()
    {
        $validate = new Validate();

        if ($validate->isPhoneValid($this->getRequestData('phone'))) {
            return $this->getRequestData('phone');
        }
    }

    public function getEmail()
    {
        if (!isset($this->data['email'])) {
            return null;
        }

        return $this->getRequestData('email');
    }

    public function getNetwork()
    {
        return $this->getRequestData('network');
    }

    public function getPayRecipientBody()
    {
        return [
            'type' => $this->getType(),
            'pay_recipient' => [
                'firstName' => $this->getFirstName(),
                'lastName' => $this->getLastName(),
                'email' => $this->getEmail(),
                'phone' => $this->getPhone(),
                'network' => $this->getNetwork(),
            ],
        ];
    }
}
