<?php

namespace Kopokopo\SDK\Requests\SendMoneyDestinations;

use Kopokopo\SDK\Requests\Validate;

class ExternalMpesaWallet extends Destination
{
    protected function getPhoneNumber() {
        $validator = new Validate();
        $phoneNumber = $this->getDestinationAttribute("phoneNumber");
        $validator->isPhoneValid($phoneNumber);

        return $phoneNumber;
    }

    protected function getNetwork() {
        return $this->getDestinationAttribute("network");
    }

    public function getDestinationData(): array
    {
        return [
            "type" => $this->getType(),
            "nickname" => $this->getNickname(),
            "phone_number" => $this->getPhoneNumber(),
            "network" => $this->getNetwork(),
            "amount" => $this->getAmount(),
            "description" => $this->getDescription(),
            "favourite" =>  $this->getFavourite(),
        ];
    }
}