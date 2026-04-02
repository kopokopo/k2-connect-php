<?php

namespace Kopokopo\SDK\Requests;

class WebhookSubscribeRequest extends BaseRequest
{
    public function getEventType(): string
    {
        return $this->getRequestData('eventType');
    }

    public function getUrl(): string
    {
        return $this->getRequestData('url');
    }

    public function getScope(): string
    {
        return $this->getRequestData('scope');
    }

    public function getScopeRef(): ?string
    {
        if (!isset($this->data['scopeReference']) && strtolower($this->getScope()) == 'company' ) {
            return null;
        }

        return $this->getRequestData('scopeReference');
    }

    public function enableDarajaPayload(): ?bool
    {
        return $this->data["enableDarajaPayload"] ?? null;
    }

    public function getWebhookSubscribeBody(): array
    {
        return [
            'event_type' => $this->getEventType(),
            'url' => $this->getUrl(),
            'scope' => $this->getScope(),
            'scope_reference' => $this->getScopeRef(),
            'enable_daraja_payload' => $this->enableDarajaPayload()
        ];
    }
}
