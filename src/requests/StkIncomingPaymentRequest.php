<?php

namespace Kopokopo\SDK\Requests;

class StkIncomingPaymentRequest extends BaseRequest
{
    public function getChannel()
    {
        return $this->getRequestData('paymentChannel');
    }

    public function getTillNumber()
    {
        return $this->getRequestData('tillNumber');
    }

    public function getFirstName()
    {
        if (!isset($this->data['firstName'])) {
            return null;
        }
        return $this->getRequestData('firstName');
    }

    public function getLastName()
    {
        if (!isset($this->data['lastName'])) {
            return null;
        }

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
            'till_number' => $this->getTillNumber(),
            'subscriber' => [
                'first_name' => $this->getFirstName(),
                'last_name' => $this->getLastName(),
                'phone_number' => $this->getPhoneNumber(),
                'email' => $this->getEmail(),
            ],
            'amount' => [
                'currency' => $this->getCurrency(),
                'value' => $this->getAmount(),
            ],
            'metadata' => $this->getMetadata(),
            '_links' => [
                'callback_url' => $this->getUrl(),
            ],
        ];
    }
}
