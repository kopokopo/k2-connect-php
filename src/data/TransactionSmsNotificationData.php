<?php

namespace Kopokopo\SDK\Data;

class TransactionSmsNotificationData
{
    public static function setData($result)
    {
        $data['id'] = $result['id'];
        $data['type'] = $result['type'];

        $data['status'] = $result['attributes']['status'];
        $data['message'] = $result['attributes']['message'];
        $data['webhookEventReference'] = $result['attributes']['webhook_event_reference'];

        $data['linkSelf'] = $result['attributes']['_links']['self'];
        $data['callbackUrl'] = $result['attributes']['_links']['callback_url'];
       
        return $data;
    }
}