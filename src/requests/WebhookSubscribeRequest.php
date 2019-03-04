<?php

namespace Kopokopo\SDK\Requests;

class WebhookSubscribeRequest extends BaseRequest
{
    public function getEventType()
    {
        return $this->getRequestData('eventType');
    }

    public function getUrl()
    {
        return $this->getRequestData('url');
    }

    public function getWebhookSecret()
    {
        return $this->getRequestData('webhookSecret');
    }

    public function getWebhookSubscribeBody()
    {
        return [
            'event_type' => $this->getEventType(),
            'url' => $this->getUrl(),
            'webhook_secret' => $this->getWebhookSecret(),
        ];
    }
}
