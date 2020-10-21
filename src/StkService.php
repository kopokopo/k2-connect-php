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

            return $this->success($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function incomingPaymentRequestStatus($options)
    {
        $stkStatus = new StatusRequest($options);
        try {
            $response = $this->client->get('incoming_payments', ['query' => $stkStatus->getLocation(), 'headers' => $stkStatus->getHeaders()]);

            return $this->success($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
