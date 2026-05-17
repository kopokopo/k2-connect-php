<?php

namespace Kopokopo\SDK;

use Kopokopo\SDK\Requests\ReversalRequest;
use Kopokopo\SDK\Data\FailedResponseData;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\BadResponseException;
use Exception;

class ReversalService extends Service
{
    public function initiateReversal($options): array
    {
        $reversalRequest = new ReversalRequest($options);

        try {
            $response = $this->client->post(
                "reversals",
                [
                    "body" => json_encode($reversalRequest->getReversalRequestBody()),
                    "headers" => $reversalRequest->getHeaders(),
                ]
            );

            return $this->postSuccess($response);
        } catch (BadResponseException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setErrorData($e));
        } catch (GuzzleException | Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}