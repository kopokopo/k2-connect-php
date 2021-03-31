<?php

namespace Kopokopo\SDK\Data;

class PaymentData
{
    public function setData($result)
    {
        $data['id'] = $result['id'];
        $data['type'] = $result['type'];

        $data['createdAt'] = $result['attributes']['created_at'];
        $data['status'] = $result['attributes']['status'];

        $data['transferBatches'] = $result['attributes']['transfer_batches'];

        $data['amount'] = $result['attributes']['amount']['value'];
        $data['currency'] = $result['attributes']['amount']['currency'];

        $data['metadata'] = $result['attributes']['metadata'];

        $data['linkSelf'] = $result['_links']['self'];
        $data['callbackUrl'] = $result['_links']['callback_url'];
       
        return $data;
    }
}