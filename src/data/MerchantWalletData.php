<?php

namespace Kopokopo\SDK\Data;

class MerchantWalletData
{
    public static function setData($result)
    {
        $data['id'] = $result['id'];
        $data['type'] = $result['type'];

        $data['phoneNumber'] = $result['attributes']['phone_number'];
        $data['network'] = $result['attributes']['network'];
        $data['firstName'] = $result['attributes']['first_name'];
        $data['lastName'] = $result['attributes']['last_name'];

        $data['status'] = $result['attributes']['status'];
        $data['accountReference'] = $result['attributes']['account_reference'];
       
        return $data;
    }
}
