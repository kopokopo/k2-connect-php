<?php

namespace Kopokopo\SDK\Requests;

class PaymentLinkRequest extends BaseRequest
{
    public function getPaymentLinkRequestBody(): array
    {
        return [
            "till_number" => $this->getTillNumber(),
            "currency" => $this->getCurrency(),
            "amount" => $this->getAmount(),
            "payment_reference" => $this->getPaymentReference(),
            "note" => $this->getNote(),
            "metadata" => $this->getMetadata(),
            "_links" => [
                "callback_url" => $this->getCallbackUrl(),
            ],
        ];
    }

    private function getTillNumber(): string
    {
        return $this->getRequestData("tillNumber");
    }

    private function getCurrency(): string
    {
        return $this->getRequestData("currency");
    }

    private function getAmount(): float
    {
        return $this->getRequestData("amount");
    }

    private function getPaymentReference(): ?string
    {
        if (!isset($this->data["paymentReference"])) return null;

        return $this->getRequestData("paymentReference");
    }

    private function getNote(): ?string
    {
        if (!isset($this->data["note"])) return null;

        return $this->getRequestData("note");
    }

    private function getMetadata(): ?array
    {
        if (!isset($this->data["metadata"])) return null;

        return $this->getRequestData("metadata");
    }

    private function getCallbackUrl(): string
    {
        return $this->getRequestData("callbackUrl");
    }
}