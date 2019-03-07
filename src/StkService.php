<?php

namespace Kopokopo\SDK;

require 'vendor/autoload.php';

use Kopokopo\SDK\Requests\StkPaymentRequest;
use Kopokopo\SDK\Requests\StatusRequest;
use Exception;

class StkService extends Service
{
    public function paymentRequest($options)
    {
        $stkPaymentrequest = new StkPaymentRequest($options);
        try {
            $response = $this->client->post('payment_requests', ['body' => json_encode($stkPaymentrequest->getPaymentRequestBody()), 'headers' => $stkPaymentrequest->getHeaders()]);

            return $this->success($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function paymentRequestStatus($options)
    {
        $stkStatus = new StatusRequest($options);
        try {
            $response = $this->client->get('payment_status', ['query' => $stkStatus->getLocation(), 'headers' => $stkStatus->getHeaders()]);

            return $this->success($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
