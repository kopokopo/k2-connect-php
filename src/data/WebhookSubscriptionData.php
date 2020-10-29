<?php

namespace Kopokopo\SDK\Data;

class WebhookSubscriptionData
{
    public function setData($result)
    {
        $result = $result['data'];

        $data['id'] = $result['id'];
        $data['type'] = $result['type'];

        $data['eventType'] = $result['attributes']['event_type'];
        $data['webhookUri'] = $result['attributes']['webhook_uri'];

        $data['secret'] = $result['attributes']['secret'];
        $data['status'] = $result['attributes']['status'];
        $data['scope'] = $result['attributes']['scope'];
        $data['scopeReference'] = $result['attributes']['scope_reference'];
       
        return $data;
    }
}
