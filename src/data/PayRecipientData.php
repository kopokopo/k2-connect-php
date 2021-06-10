<?php

namespace Kopokopo\SDK\Data;

class PayRecipientData
{
    public static function setData($result)
    {
        $data['id'] = $result['id'];
        $data['type'] = $result['type'];

        $data['recipientType'] = $result['attributes']['recipient_type'];

        switch ($result['attributes']['recipient_type']) {
            case 'Bank Account':
                $data['accountNumber'] = $result['attributes']['account_number'];
                $data['accountName'] = $result['attributes']['account_name'];
                $data['bankBranchRef'] = $result['attributes']['bank_branch_ref'];
                $data['settlementMethod'] = $result['attributes']['settlement_method'];
               
                break;
            case 'Till':
                $data['tillNumber'] = $result['attributes']['till_number'];
                $data['tillName'] = $result['attributes']['till_name'];
               
                break;
            case 'Kopo Kopo Merchant':
                $data['tillNumber'] = $result['attributes']['till_number'];
                $data['aliasName'] = $result['attributes']['alias_name'];
               
                break;
            default:
                // mobile
                $data['phoneNumber'] = $result['attributes']['phone_number'];
                $data['network'] = $result['attributes']['network'];
                $data['firstName'] = $result['attributes']['first_name'];
                $data['lastName'] = $result['attributes']['last_name'];
                $data['email'] = $result['attributes']['email'];

                break;
        }
        

        $data['status'] = $result['attributes']['status'];
        $data['recipientReference'] = $result['attributes']['recipient_reference'];
       
        return $data;
    }
}
