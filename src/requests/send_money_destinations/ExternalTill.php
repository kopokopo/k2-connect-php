<?php

namespace Kopokopo\SDK\Requests\SendMoneyDestinations;

class ExternalTill extends Destination
{
    protected function getTillNumber() {
        return $this->getDestinationAttribute("tillNumber");
    }

    public function getDestinationData(): array
    {
        return [
            "type" =>  $this->getType(),
            "till_number" => $this->getTillNumber(),
            "nickname" => $this->getNickname(),
            "amount" => $this->getAmount(),
            "description" => $this->getDescription(),
            "favourite" => $this->getFavourite(),
        ];
    }
}