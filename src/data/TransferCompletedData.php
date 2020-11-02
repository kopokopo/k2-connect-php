<?php

namespace Kopokopo\SDK\Data;

class TransferCompletedData
{
    public function setData($result)
    {
        $data['id'] = $result['id'];
        
        $data['topic'] = $result['topic'];
        $data['createdAt'] = $result['created_at'];

        $data['eventType'] = $result['event']['type'];

        $data['resourceId'] = $result['event']['resource']['id'];
        $data['createdAt'] = $result['event']['resource']['createdAt'];
        $data['amount'] = $result['event']['resource']['amount'];
        $data['currency'] = $result['event']['resource']['currency'];
        $data['status'] = $result['event']['resource']['status'];

        $data['destinationType'] = $result['event']['resource']['destination']['type'];
        $data['destinationReference'] = $result['event']['resource']['destination']['resource']['reference'];

        // To account for different destination types
        switch ($result['event']['resource']['destination']['type']) {
            case 'Bank Account':
                $data['settlementMethod'] = $result['event']['resource']['destination']['settlement_method'];
                $data['bankBranchref'] = $result['event']['resource']['destination']['bank_branch_ref'];
                $data['accountName'] = $result['event']['resource']['destination']['account_name'];
                $data['accountNumber'] = $result['event']['resource']['destination']['account_number'];

                break;
            default:
                // mobile
                $data['firstName'] = $result['event']['resource']['destination']['resource']['first_name'];
                $data['lastName'] = $result['event']['resource']['destination']['resource']['last_name'];
                $data['phoneNumber'] = $result['event']['resource']['destination']['resource']['phone_number'];
                $data['network'] = $result['event']['resource']['destination']['resource']['network'];

                break;
        }

        $data['linkSelf'] = $result['_links']['self'];
        $data['linkResource'] = $result['_links']['resource'];

        return $data;
    }
}
