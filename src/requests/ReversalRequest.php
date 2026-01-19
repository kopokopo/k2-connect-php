<?php

namespace Kopokopo\SDK\Requests;

class ReversalRequest extends BaseRequest
{
    public function getReversalRequestBody(): array {
        return [
            "transaction_reference" => $this->getTransactionReference(),
            "reason" => $this->getReversalReason(),
            "metadata" => $this->getMetadata(),
            "_links" => [
                "callback_url" => $this->getCallbackUrl(),
            ],
        ];
    }

    private function getTransactionReference(): string
    {
        return $this->getRequestData("transactionReference");
    }

    private function getReversalReason(): string
    {
        return $this->getRequestData("reason");
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