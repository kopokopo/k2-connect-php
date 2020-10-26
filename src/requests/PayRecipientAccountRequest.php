<?php

namespace Kopokopo\SDK\Requests;

class PayRecipientAccountRequest extends BaseRequest
{
    public function getType()
    {
        return $this->getRequestData('type');
    }

    public function getAccountName()
    {
        return $this->getRequestData('accountName');
    }

    public function getBankBranchRef()
    {
        return $this->getRequestData('bankBranchRef');
    }

    public function getAccountNumber()
    {
        return $this->getRequestData('accountNumber');
    }

    public function getSettlementMethod()
    {
        return $this->getRequestData('settlementMethod');
    }

    public function getPayRecipientBody()
    {
        return [
            'type' => $this->getType(),
            'pay_recipient' => [
                'account_name' => $this->getAccountName(),
                'bank_branch_ref' => $this->getBankBranchRef(),
                'account_number' => $this->getAccountNumber(),
                'settlement_method' => $this->getSettlementMethod(),
            ],
        ];
    }
}
