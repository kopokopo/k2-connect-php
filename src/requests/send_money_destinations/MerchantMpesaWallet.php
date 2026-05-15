<?php

namespace Kopokopo\SDK\Requests\SendMoneyDestinations;

class MerchantMpesaWallet extends Destination
{
    public function getDestinationData(): array
    {
        return [
            "type" => $this->getType(),
            "reference" => $this->getReference(),
            "amount" => $this->getAmount(),
        ];
    }
}