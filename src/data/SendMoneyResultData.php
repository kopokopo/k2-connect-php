<?php

namespace Kopokopo\SDK\Data;

use Kopokopo\SDK\Helpers\K2Utils;

class SendMoneyResultData
{
    public static function setData($result): array
    {
        $data["id"] = $result["id"];
        $data["type"] = $result["type"];
        $data["createdAt"] = $result["attributes"]["created_at"];
        $data["status"] = $result["attributes"]["status"];
        $data["sourceIdentifier"] = $result["attributes"]["source_identifier"];
        $data["destinations"] = K2Utils::deepCamelizeKeys($result["attributes"]["destinations"]);
        $data["currency"] = $result["attributes"]["currency"];
        $data["transferBatches"] = K2Utils::deepCamelizeKeys($result["attributes"]["transfer_batches"]);
        $data["errors"] = $result["attributes"]["errors"];
        $data["metadata"] = $result["attributes"]["metadata"];
        $data['linkSelf'] = $result["attributes"]["_links"]["self"];
        $data['callbackUrl'] = $result["attributes"]["_links"]["callback_url"];

        return $data;
    }
}

