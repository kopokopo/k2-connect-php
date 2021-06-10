<?php

namespace Kopokopo\SDK\Data;

class B2bReceivedData
{
    public static function setData($result)
    {
        $data['id'] = $result['id'];
        $data['topic'] = $result['topic'];
        $data['createdAt'] = $result['created_at'];

        $data['eventType'] = $result['event']['type'];

        $data['resourceId'] = $result['event']['resource']['id'];
        $data['reference'] = $result['event']['resource']['reference'];
        $data['originationTime'] = $result['event']['resource']['origination_time'];

        $data['sendingTill'] = $result['event']['resource']['sending_till'];
        $data['amount'] = $result['event']['resource']['amount'];
        $data['currency'] = $result['event']['resource']['currency'];
        $data['tillNumber'] = $result['event']['resource']['till_number'];
        $data['system'] = $result['event']['resource']['system'];
        $data['status'] = $result['event']['resource']['status'];

        $data['linkSelf'] = $result['_links']['self'];
        $data['linkResource'] = $result['_links']['resource'];

        return $data;
    }
}
