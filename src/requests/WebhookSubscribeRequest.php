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

    public function getScope()
    {
        return $this->getRequestData('scope');
    }

    public function getScopeRef()
    {
        if (!isset($this->data['scopeReference']) && strtolower($this->getScope()) == 'company' ) {
            return null;
        }

        return $this->getRequestData('scopeReference');
    }

    public function getWebhookSubscribeBody()
    {
        return [
            'event_type' => $this->getEventType(),
            'url' => $this->getUrl(),
            'scope' => $this->getScope(),
            'scope_reference' => $this->getScopeRef()
        ];
    }
}
