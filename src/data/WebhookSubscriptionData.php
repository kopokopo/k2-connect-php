<?php

namespace Kopokopo\SDK\Data;

class WebhookSubscriptionData
{
    public static function setData($result)
    {
        $data['id'] = $result['id'];
        $data['type'] = $result['type'];

        $data['eventType'] = $result['attributes']['event_type'];
        $data['webhookUri'] = $result['attributes']['webhook_uri'];

        $data['status'] = $result['attributes']['status'];
        $data['scope'] = $result['attributes']['scope'];
        $data['scopeReference'] = $result['attributes']['scope_reference'];
       
        return $data;
    }
}
