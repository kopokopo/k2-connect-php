<?php

namespace Kopokopo\SDK\Requests;

class SettlementAccountRequest extends BaseRequest
{
    public function getAccountName()
    {
        return $this->getRequestData('accountName');
    }

    public function getBankRef()
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

    public function getSettlementAccountBody()
    {
        return [
            'account_name' => $this->getAccountName(),
            'bank_ref' => $this->getBankRef(),
            'bank_branch_ref' => $this->getBankBranchRef(),
            'account_number' => $this->getAccountNumber(),
        ];
    }
}
