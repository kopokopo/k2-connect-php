<?php

namespace Kopokopo\SDK\Requests;

use Kopokopo\SDK\Requests\SendMoneyDestinations\ExternalBankAccount;
use Kopokopo\SDK\Requests\SendMoneyDestinations\ExternalMpesaWallet;
use Kopokopo\SDK\Requests\SendMoneyDestinations\ExternalPaybill;
use Kopokopo\SDK\Requests\SendMoneyDestinations\ExternalTill;
use Kopokopo\SDK\Requests\SendMoneyDestinations\MerchantBankAccount;
use Kopokopo\SDK\Requests\SendMoneyDestinations\MerchantMpesaWallet;

class SendMoneyRequest extends BaseRequest
{
    public function getSendMoneyRequestBody(): array
    {
        return [
            "destinations" => $this->getDestinations(),
            "currency" => $this->getCurrency(),
            "source_identifier" => $this->getSourceIdentifier(),
            "metadata" => $this->getMetadata(),
            "_links" => [
                "callback_url" => $this->getUrl(),
            ]
        ];
    }

    private function getDestinations(): ?array
    {
        if (!isset($this->data["destinations"]) || null) return null;

        return array_map(function ($destination): array {
            if (!isset($destination["type"])) throw new \InvalidArgumentException("You have to provide the destination type");

            switch($destination["type"]) {
                case "merchant_wallet":
                    return (new MerchantMpesaWallet($destination))->getDestinationData();
                case "merchant_bank_account":
                    return (new MerchantBankAccount($destination))->getDestinationData();
                case "mobile_wallet":
                    return (new ExternalMpesaWallet($destination))->getDestinationData();
                case "bank_account":
                    return (new ExternalBankAccount($destination))->getDestinationData();
                case "till":
                    return (new ExternalTill($destination))->getDestinationData();
                case "paybill":
                    return (new ExternalPaybill($destination))->getDestinationData();
                default:
                    throw new \InvalidArgumentException("Invalid destination type");
            }
        }, $this->getRequestData("destinations"));
    }

    private function getCurrency(): string {
        return $this->getRequestData("currency");
    }

    private function getSourceIdentifier(): ?string {
        if (!isset($this->data["sourceIdentifier"])) return null;

        return $this->getRequestData("sourceIdentifier");
    }

    private function getMetadata(): ?array
    {
        if (!isset($this->data["metadata"])) return null;

        return $this->getRequestData("metadata");
    }

    private function getUrl(): string
    {
        return $this->getRequestData("callbackUrl");
    }
}