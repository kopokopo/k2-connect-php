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

    public function getPhoneNumber()
    {
        $validate = new Validate();

        if ($validate->isPhoneValid($this->getRequestData('phoneNumber'))) {
            return $this->getRequestData('phoneNumber');
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
                'first_name' => $this->getFirstName(),
                'last_name' => $this->getLastName(),
                'email' => $this->getEmail(),
                'phone_number' => $this->getPhoneNumber(),
                'network' => $this->getNetwork(),
            ],
        ];
    }
}
