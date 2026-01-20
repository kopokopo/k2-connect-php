<?php

namespace Kopokopo\SDK;

use Kopokopo\SDK\Requests\PaymentLinkRequest;
use Kopokopo\SDK\Requests\PaymentLinkCancellationRequest;
use Kopokopo\SDK\Data\FailedResponseData;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\BadResponseException;
use \Exception;

class PaymentLinkService extends Service
{
    public function createPaymentLink(array $options): array {
        $paymentLinkRequest = new PaymentLinkRequest($options);
        try {
            $response = $this->client->post(
                "payment_links",
                [
                    "body" => json_encode($paymentLinkRequest->getPaymentLinkRequestBody()),
                    "headers" => $paymentLinkRequest->getHeaders()
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

    public function cancelPaymentLink(array $options): array {
        $paymentLinkCancellationRequest = new PaymentLinkCancellationRequest($options);
        try {
            $response = $this->client->post(
                $paymentLinkCancellationRequest->getCancellationURL(),
                [
                    "headers" => $paymentLinkCancellationRequest->getHeaders()
                ]
            );

            return [
                "status" => "success",
                "message" => json_decode($response->getBody()->getContents(), true)["message"],
            ];
        } catch (BadResponseException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setErrorData($e));
        } catch (GuzzleException | Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}