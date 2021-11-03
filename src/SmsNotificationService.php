<?php

namespace Kopokopo\SDK;

// require 'vendor/autoload.php';

use Kopokopo\SDK\Requests\TransactionSmsNotificationRequest;
use Kopokopo\SDK\Data\FailedResponseData;
use Exception;

class SmsNotificationService extends Service
{
    public function sendTransactionSmsNotification($options)
    {
        $transactionNotificationRequest = new TransactionSmsNotificationRequest($options);
        try {
            $response = $this->client->post('transaction_sms_notifications', ['body' => json_encode($transactionNotificationRequest->getSmsNotificationBody()), 'headers' => $transactionNotificationRequest->getHeaders()]);

            return $this->postSuccess($response);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setErrorData($e));
        } catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}
