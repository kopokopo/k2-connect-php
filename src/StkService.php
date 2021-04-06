<?php

namespace Kopokopo\SDK;

require 'vendor/autoload.php';

use Kopokopo\SDK\Requests\StkIncomingPaymentRequest;
use Kopokopo\SDK\Data\FailedResponseData;
use Exception;

class StkService extends Service
{
    public function initiateIncomingPayment($options)
    {
        $stkPaymentRequest = new StkIncomingPaymentRequest($options);
        try {
            $response = $this->client->post('incoming_payments', ['body' => json_encode($stkPaymentRequest->getPaymentRequestBody()), 'headers' => $stkPaymentRequest->getHeaders()]);

            return $this->postSuccess($response);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setErrorData(json_decode($e->getResponse()->getBody()->getContents(), true)));
        } catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}
