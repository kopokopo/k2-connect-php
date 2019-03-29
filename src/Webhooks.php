<?php

namespace Kopokopo\SDK;

require 'vendor/autoload.php';

use Kopokopo\SDK\Requests\WebhookSubscribeRequest;
use Kopokopo\SDK\Helpers\Auth;
use Kopokopo\SDK\Data\DataHandler;
use InvalidArgumentException;

class Webhooks extends Service
{
    public function webhookHandler($details, $signature)
    {
        if (empty($details) || empty($signature)) {
            return $this->error('Pass the payload and signature ');
        }

        $auth = new Auth();

        $statusCode = $auth->auth($details, $signature, $this->clientSecret);

        if ($statusCode == 200) {
            $dataHandler = new DataHandler(json_decode($details, true));

            return $this->webhookSuccess($dataHandler->dataHandlerSort($details));
        } else {
            return $this->error('Unauthorized');
        }
    }

    public function subscribe($options)
    {
        $subscribeRequest = new WebhookSubscribeRequest($options);

        try {
            $response = $this->client->post('webhook-subscriptions', ['body' => json_encode($subscribeRequest->getWebhookSubscribeBody()), 'headers' => $subscribeRequest->getHeaders()]);

            return $this->success($response);
        } catch (InvalidArgumentException $e) {
            return $this->error($e->getMessage());
        }
    }
}
