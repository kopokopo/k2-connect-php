<?php

namespace Kopokopo\SDK\Requests\SendMoneyDestinations;


class ExternalPaybill extends Destination
{
    protected function getPaybillNumber() {
        return $this->getDestinationAttribute("paybillNumber");
    }

    protected function getPaybillAccountNumber() {
        return $this->getDestinationAttribute("paybillAccountNumber");
    }

    public function getDestinationData(): array
    {
        return [
            "type" => $this->getType(),
            "paybill_number" => $this->getPaybillNumber(),
            "paybill_account_number" => $this->getPaybillAccountNumber(),
            "nickname" => $this->getNickname(),
            "amount" => $this->getAmount(),
            "description" => $this->getDescription(),
            "favourite" => $this->getFavourite(),
        ];
    }
}