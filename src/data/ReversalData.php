<?php

namespace Kopokopo\SDK\Data;

use Kopokopo\SDK\Helpers\K2Utils;

class ReversalData
{
    public static function setData($result): array
    {
        return  [
            "id" => $result["id"],
            "type" => $result["type"],
            "transactionReference" => $result["attributes"]["transaction_reference"],
            "status" => $result["attributes"]["status"],
            "reason" => $result["attributes"]["reason"],
            "reversalBulkPayment" => K2Utils::deepCamelizeKeys($result["attributes"]["reversal_bulk_payment"]),
            "errors" => $result["attributes"]["errors"],
            "metadata" => $result["attributes"]["metadata"],
            "createdAt" => $result["attributes"]["created_at"],
            "callbackUrl" => $result["attributes"]["_links"]["callback_url"],
            "linkSelf" => $result["attributes"]["_links"]["self"],
        ];
    }
}