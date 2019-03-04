<?php

namespace Kopokopo\SDK;

require 'vendor/autoload.php';

use Kopokopo\SDK\Requests\PayRecipientMobileRequest;
use Kopokopo\SDK\Requests\PayRecipientAccountRequest;
use Kopokopo\SDK\Requests\PayRequest;
use Kopokopo\SDK\Requests\StatusRequest;
use Exception;

class PayService extends Service
{
    public function addPayRecipient($options)
    {
        try {
            if(!isset($options['type'])){
                throw new \InvalidArgumentException("You have to provide the type");
            }
            else if ($options['type'] === 'bank_account') {
                $payRecipientrequest = new PayRecipientAccountRequest($options);
            } else {
                $payRecipientrequest = new PayRecipientMobileRequest($options);
            }

            $response = $this->client->post('pay_recipients', ['body' => json_encode($payRecipientrequest->getPayRecipientBody()), 'headers' => $payRecipientrequest->getHeaders()]);

            return $this->success($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function sendPay($options)
    {
        $payRequest = new PayRequest($options);
        try {
            $response = $this->client->post('pay', ['body' => json_encode($payRequest->getPayBody()), 'headers' => $payRequest->getHeaders()]);

            return $this->success($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function payStatus($options)
    {
        $payStatus = new StatusRequest($options);
        try {
            $response = $this->client->get('pay_status', ['query' => $payStatus->getLocation(), 'headers' => $payStatus->getHeaders()]);

            return $this->success($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
