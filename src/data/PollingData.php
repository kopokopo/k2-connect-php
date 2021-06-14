<?php

namespace Kopokopo\SDK\Data;

class PollingData
{
    public static function setData($result)
    {
        $data['id'] = $result['id'];
        $data['type'] = $result['type'];

        $data['status'] = $result['attributes']['status'];
        $data['fromTime'] = $result['attributes']['from_time'];
        $data['toTime'] = $result['attributes']['to_time'];
        $data['scope'] = $result['attributes']['scope'];
        $data['scopeReference'] = $result['attributes']['scope_reference'];
        $data['transactions'] = $result['attributes']['transactions'];

        $data['linkSelf'] = $result['attributes']['_links']['self'];
        $data['callbackUrl'] = $result['attributes']['_links']['callback_url'];
       
        return $data;
    }
}