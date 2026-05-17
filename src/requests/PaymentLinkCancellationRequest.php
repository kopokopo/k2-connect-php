<?php

namespace Kopokopo\SDK\Requests;

class PaymentLinkCancellationRequest extends BaseRequest
{
    public function getCancellationURL(): string {
        return $this->getLocation() ."/cancel";
    }

    private function getLocation() {
        return $this->getRequestData("location");
    }
}