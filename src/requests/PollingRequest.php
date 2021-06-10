<?php

namespace Kopokopo\SDK\Requests;

class PollingRequest extends BaseRequest
{

    public function getFromTime()
    {
        return $this->getRequestData('fromTime');
    }

    public function getToTime()
    {
        return $this->getRequestData('toTime');
    }

    public function getScope()
    {
        return $this->getRequestData('scope');
    }

    public function getScopeReference()
    {
        if (!isset($this->data['scopeReference']) && strtolower($this->getScope()) == 'company' ) {
            return null;
        }

        return $this->getRequestData('scopeReference');
    }

    public function getUrl()
    {
        return $this->getRequestData('callbackUrl');
    }

    public function getPollingRequestBody()
    {
        return [
            'from_time' => $this->getFromTime(),
            'to_time' => $this->getToTime(),
            'scope' => $this->getScope(),
            'scope_reference' => $this->getScopeReference(),
            '_links' => [
                'callback_url' => $this->getUrl(),
            ],
        ];
    }
}
