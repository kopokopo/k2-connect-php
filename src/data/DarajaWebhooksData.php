<?php

namespace Kopokopo\SDK\Data;

class DarajaWebhooksData
{
    public static function setData($result)
    {
        $data['transactionType'] = $result['TransactionType'];
        $data['transactionId'] = $result['TransID'];
        $data['transactionTime'] = $result['TransTime'];
        $data['transactionAmount'] = $result['TransAmount'];
        $data['businessShortCode'] = $result['BusinessShortCode'];
        $data['billRefNumber'] = $result['BillRefNumber'];
        $data['invoiceNumber'] = $result['InvoiceNumber'];
        $data['orgAccountBalance'] = $result['OrgAccountBalance'];
        $data['thirdPartyTransactionId'] = $result['ThirdPartyTransId'];
        $data['msisdn'] = $result['MSISDN'];
        $data['firstName'] = $result['FirstName'];
        $data['middleName'] = $result['MiddleName'];
        $data['lastName'] = $result['LastName'];

        return $data;
    }
}
