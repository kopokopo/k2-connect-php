<?php

namespace Kopokopo\SDK\Data;

use Kopokopo\SDK\Helpers\K2Utils;

class PaymentLinkData
{
    public static function setData(array $result): array
    {
        return [
            "id" => $result["id"],
            "type" => $result["type"],
            "status" => $result["attributes"]["status"],
            "currency" => $result["attributes"]["currency"],
            "amount" => $result["attributes"]["amount"],
            "tillName" => $result["attributes"]["till_name"],
            "tillNumber" => $result["attributes"]["till_number"],
            "paymentReference" => $result["attributes"]["payment_reference"],
            "note" => $result["attributes"]["note"],
            "createdAt" => $result["attributes"]["created_at"],
            "paymentLink" => K2Utils::deepCamelizeKeys($result["attributes"]["payment_link"]),
            "errors" => $result["attributes"]["errors"],
            "metadata" => $result["attributes"]["metadata"],
            "callbackUrl" => $result["attributes"]["_links"]["callback_url"],
            "linkSelf" => $result["attributes"]["_links"]["self"],
        ];
    }
}
