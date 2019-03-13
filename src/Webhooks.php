<?php

namespace Kopokopo\SDK;

use hash_hmac;

require 'vendor/autoload.php';

use Kopokopo\SDK\Requests\WebhookSubscribeRequest;
use InvalidArgumentException;

class Webhooks extends Service
{
    public function webhookHandler($details, $signature)
    {
        if (empty($details) || empty($signature)) {
            return $this->error('Pass the payload and signature ');
        }

        $statusCode = $this->auth($details, $signature);

        // TODO: return the whole body or what? figure out.
        if ($statusCode == 200) {
            return $this->webhookSuccess($details);
        } else {
            return $this->error('Unauthorized');
        }
    }

    // TODO: The hash is not returning correct value when mocking; figure out why
    public function auth($details, $signature)
    {
        $expectedSignature = hash_hmac('sha256', $details, $this->clientSecret);

        print_r($expectedSignature);

        if (hash_equals($signature, $expectedSignature)) {
            return 200;
        } else {
            return 401;
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

    //TODO: Finish up on this; In the case I will have to digest webhook data.
    public function resourceDetails($details)
    {
    }
}
