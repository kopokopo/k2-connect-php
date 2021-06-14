<?php

namespace Kopokopo\SDK\Requests;

class TransactionSmsNotificationRequest extends BaseRequest
{
    public function getWebhookEventReference()
    {
        return $this->getRequestData('webhookEventReference');
    }

    public function getMessage()
    {
        return $this->getRequestData('message');
    }

    public function getUrl()
    {
        return $this->getRequestData('callbackUrl');
    }

    public function getSmsNotificationBody()
    {
        return [
            'webhook_event_reference' => $this->getWebhookEventReference(),
            'message' => $this->getMessage(),
            '_links' => [
                'callback_url' => $this->getUrl(),
            ],
        ];
    }
}
