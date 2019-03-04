<?php

namespace Kopokopo\SDK\Requests;

class StatusRequest extends BaseRequest
{
    public function getLocation()
    {
        return $this->getRequestData('location');
    }
}
