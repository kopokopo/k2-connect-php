<?php

namespace Kopokopo\SDK\Data;

class PaymentData
{
    public static function setData($result)
    {
        $data['id'] = $result['id'];
        $data['type'] = $result['type'];

        $data['createdAt'] = $result['attributes']['created_at'];
        $data['status'] = $result['attributes']['status'];

        $data['transferBatches'] = $result['attributes']['transfer_batches'];

        $data['amount'] = $result['attributes']['amount']['value'];
        $data['currency'] = $result['attributes']['amount']['currency'];

        $data['metadata'] = $result['attributes']['metadata'];

        $data['linkSelf'] = $result['attributes']['_links']['self'];
        $data['callbackUrl'] = $result['attributes']['_links']['callback_url'];
       
        return $data;
    }
}