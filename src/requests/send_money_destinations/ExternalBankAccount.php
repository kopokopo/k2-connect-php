<?php

namespace Kopokopo\SDK\Requests\SendMoneyDestinations;

class ExternalBankAccount extends Destination
{
    protected function getBankBranchReference() {
        return $this->getDestinationAttribute("bankBranchRef");
    }

    protected function getAccountName() {
        return $this->getDestinationAttribute("accountName");
    }

    protected function getAccountNumber() {
        return $this->getDestinationAttribute("accountNumber");
    }

    public function getDestinationData(): array
    {
        return [
            "type" => $this->getType(),
            "bank_branch_ref" => $this->getBankBranchReference(),
            "account_name" => $this->getAccountName(),
            "account_number" => $this->getAccountNumber(),
            "nickname" => $this->getNickname(),
            "amount" => $this->getAmount(),
            "description" => $this->getDescription(),
            "favourite" => $this->getFavourite(),
        ];
    }
}