<?php

namespace Kopokopo\SDK;

require 'vendor/autoload.php';

use Kopokopo\SDK\Requests\PayRecipientMobileRequest;
use Kopokopo\SDK\Requests\PayRecipientAccountRequest;
use Kopokopo\SDK\Requests\PayRecipientTillRequest;
use Kopokopo\SDK\Requests\PayRecipientMerchantRequest;
use Kopokopo\SDK\Requests\PayRequest;
use Kopokopo\SDK\Data\FailedResponseData;
use Exception;

use GuzzleHttp\Client;


class PayService extends Service
{
    public function addPayRecipient($options)
    {
        try {
            if (!isset($options['type'])) {
                throw new \InvalidArgumentException('You have to provide the type');
            } elseif ($options['type'] === 'bank_account') {
                $payRecipientrequest = new PayRecipientAccountRequest($options);
            } elseif ($options['type'] === 'till') {
                $payRecipientrequest = new PayRecipientTillRequest($options);
            } elseif ($options['type'] === 'kopo_kopo_merchant') {
                $payRecipientrequest = new PayRecipientMerchantRequest($options);
            } elseif ($options['type'] === 'mobile_wallet') {
                $payRecipientrequest = new PayRecipientMobileRequest($options);
            } else{
                throw new \InvalidArgumentException('Invalid recipient type');
            }

            $response = $this->client->post('pay_recipients', ['body' => json_encode($payRecipientrequest->getPayRecipientBody()), 'headers' => $payRecipientrequest->getHeaders()]);

            return $this->postSuccess($response);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setErrorData(json_decode($e->getResponse()->getBody()->getContents(), true)));
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function sendPay($options)
    {
        $payRequest = new PayRequest($options);
        try {
            $response = $this->client->post('payments', ['body' => json_encode($payRequest->getPayBody()), 'headers' => $payRequest->getHeaders()]);

            return $this->postSuccess($response);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setErrorData(json_decode($e->getResponse()->getBody()->getContents(), true)));
        } catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}
