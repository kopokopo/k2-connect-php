<?php

namespace Kopokopo\SDK\Requests;

class MerchantBankAccountRequest extends BaseRequest
{
    public function getAccountName()
    {
        return $this->getRequestData('accountName');
    }

    public function getSettlementMethod()
    {
        return $this->getRequestData('settlementMethod');
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
            'settlement_method' => $this->getSettlementMethod(),
            'bank_branch_ref' => $this->getBankBranchRef(),
            'account_number' => $this->getAccountNumber(),
        ];
    }
}
