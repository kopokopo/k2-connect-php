<?php

namespace Kopokopo\SDK\Data;

class StkData
{
    public static function setData($result)
    {
        $data['id'] = $result['id'];
        $data['type'] = $result['type'];

        $data['initiationTime'] = $result['attributes']['initiation_time'];
        $data['status'] = $result['attributes']['status'];

        $data['eventType'] = $result['attributes']['event']['type'];

        switch ($result['attributes']['status']) {
            case 'Failed':
                $data['resource'] = $result['attributes']['event']['resource'];
                break;
            case 'Pending':
                $data['resource'] = $result['attributes']['event']['resource'];
                break;
            default:
                $data['resourceId'] = $result['attributes']['event']['resource']['id'];
                $data['reference'] = $result['attributes']['event']['resource']['reference'];
                $data['originationTime'] = $result['attributes']['event']['resource']['origination_time'];
                $data['senderPhoneNumber'] = $result['attributes']['event']['resource']['sender_phone_number'];
                $data['amount'] = $result['attributes']['event']['resource']['amount'];
                $data['currency'] = $result['attributes']['event']['resource']['currency'];
                $data['tillNumber'] = $result['attributes']['event']['resource']['till_number'];
                $data['system'] = $result['attributes']['event']['resource']['system'];
                $data['resourceStatus'] = $result['attributes']['event']['resource']['status'];
                $data['senderFirstName'] = $result['attributes']['event']['resource']['sender_first_name'];
                $data['senderMiddleName'] = $result['attributes']['event']['resource']['sender_middle_name'];
                $data['senderLastName'] = $result['attributes']['event']['resource']['sender_last_name'];

                //$data['linkResource'] = $result['attributes']['_links']['resource'];
                break;
        }

        $data['errors'] = $result['attributes']['event']['errors'];

        // metadata
        $data['metadata'] = $result['attributes']['metadata'];

        $data['linkSelf'] = $result['attributes']['_links']['self'];
        $data['callbackUrl'] = $result['attributes']['_links']['callback_url'];

        return $data;
    }
}
