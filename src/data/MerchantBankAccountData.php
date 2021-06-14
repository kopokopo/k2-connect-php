<?php

namespace Kopokopo\SDK\Data;

class MerchantBankAccountData
{
    public static function setData($result)
    {
        $data['id'] = $result['id'];
        $data['type'] = $result['type'];

        $data['accountNumber'] = $result['attributes']['account_number'];
        $data['accountName'] = $result['attributes']['account_name'];
        $data['bankBranchRef'] = $result['attributes']['bank_branch_ref'];
        $data['settlementMethod'] = $result['attributes']['settlement_method'];

        $data['status'] = $result['attributes']['status'];
        $data['accountReference'] = $result['attributes']['account_reference'];
       
        return $data;
    }
}
