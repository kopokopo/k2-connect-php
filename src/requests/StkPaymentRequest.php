<?php

namespace Kopokopo\SDK\Requests;

class StkPaymentRequest extends BaseRequest
{
    public function getChannel()
    {
        return $this->getRequestData('paymentChannel');
    }

    public function getTill()
    {
        return $this->getRequestData('tillNumber');
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

    public function getAmount()
    {
        return $this->getRequestData('amount');
    }

    public function getCurrency()
    {
        return $this->getRequestData('currency');
    }

    public function getUrl()
    {
        return $this->getRequestData('callbackUrl');
    }

    public function getMetadata()
    {
        if (!isset($this->data['metadata'])) {
            return null;
        }

        return $this->getRequestData('metadata');
    }

    public function getPaymentRequestBody()
    {
        return [
            'payment_channel' => $this->getChannel(),
            'till_identifier' => $this->getTill(),
            'subscriber' => [
                'first_name' => $this->getfirstName(),
                'last_name' => $this->getlastName(),
                'phone' => $this->getPhone(),
                'email' => $this->getEmail(),
            ],
            'amount' => [
                $this->getCurrency(),
                $this->getAmount(),
            ],
            'metadata' => $this->getMetadata(),
            '_links' => [
                'call_back_url' => $this->getUrl(),
            ],
        ];
    }
}
