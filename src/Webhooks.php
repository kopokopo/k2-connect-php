<?php

namespace Kopokopo\SDK;

// require 'vendor/autoload.php';

use Kopokopo\SDK\Requests\WebhookSubscribeRequest;
use Kopokopo\SDK\Helpers\Auth;
use Kopokopo\SDK\Data\DataHandler;
use Kopokopo\SDK\Data\FailedResponseData;
use InvalidArgumentException;

class Webhooks extends Service
{
    public function webhookHandler($details, $signature)
    {
        if (empty($details) || empty($signature)) {
            return $this->error('Pass the payload and signature ');
        }

        $auth = new Auth();

        $statusCode = $auth->auth($details, $signature, $this->apiKey);

        if ($statusCode == 200) {
            $dataHandler = new DataHandler(json_decode($details, true));

            return $this->success($dataHandler->dataHandlerSort($details));
        } else {
            return $this->error('Unauthorized');
        }
    }

    public function subscribe($options)
    {
        try {
            $subscribeRequest = new WebhookSubscribeRequest($options);
            $response = $this->client->post('webhook_subscriptions', ['body' => json_encode($subscribeRequest->getWebhookSubscribeBody()), 'headers' => $subscribeRequest->getHeaders()]);

            return $this->postSuccess($response);
        } catch (InvalidArgumentException $e) {
            return $this->error($e->getMessage());
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setErrorData($e));
        } catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}
