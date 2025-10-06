<?php

namespace Kopokopo\SDK;

use Kopokopo\SDK\Requests\SendMoneyRequest;
use Kopokopo\SDK\Data\FailedResponseData;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\BadResponseException;
use Exception;

class SendMoneyService extends Service
{
    public function sendMoney($options): array
    {
        $sendMoneyRequest = new SendMoneyRequest($options);

        try {
            $response = $this->client->post(
                "send_money",
                [
                    "body" => json_encode($sendMoneyRequest->getSendMoneyRequestBody()),
                    "headers" => $sendMoneyRequest->getHeaders(),
                ]
            );

            return $this->postSuccess($response);
        } catch(BadResponseException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setErrorData($e));
        } catch (GuzzleException | Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}