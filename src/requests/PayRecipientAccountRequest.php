<?php

namespace Kopokopo\SDK\Requests;

class PayRecipientAccountRequest extends BaseRequest
{
    public function getType()
    {
        return $this->getRequestData('type');
    }

    public function getName()
    {
        return $this->getRequestData('name');
    }

    public function getAccountName()
    {
        return $this->getRequestData('accountName');
    }

    public function getBankref()
    {
        return $this->getRequestData('bankRef');
    }

    public function getBankBranchRef()
    {
        return $this->getRequestData('bankBranchRef');
    }

    public function getAccountNumber()
    {
        return $this->getRequestData('accountNumber');
    }

    public function getEmail()
    {
        if (!isset($this->data['email'])) {
            return null;
        }

        return $this->getRequestData('email');
    }

    public function getPhone()
    {
        if (!isset($this->data['phone'])) {
            return null;
        }

        return $this->getRequestData('phone');
    }

    public function getPayRecipientBody()
    {
        return [
            'type' => $this->getType(),
            'pay_recipient' => [
                'name' => $this->getName(),
                'account_name' => $this->getAccountName(),
                'bank_id' => $this->getBankref(),
                'bank_branch_id' => $this->getBankBranchRef(),
                'account_number' => $this->getAccountNumber(),
                'email' => $this->getEmail(),
                'phone' => $this->getPhone(),
            ],
        ];
    }
}
