<?php

namespace Kopokopo\SDK;

require 'vendor/autoload.php';

use Kopokopo\SDK\Requests\StkIncomingPaymentRequest;
use Kopokopo\SDK\Requests\StatusRequest;
use Exception;

class StkService extends Service
{
    public function initiateIncomingPayment($options)
    {
        $stkPaymentRequest = new StkIncomingPaymentRequest($options);
        try {
            $response = $this->client->post('incoming_payments', ['body' => json_encode($stkPaymentRequest->getPaymentRequestBody()), 'headers' => $stkPaymentRequest->getHeaders()]);

            return $this->postSuccess($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
