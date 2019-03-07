<?php

namespace Kopokopo\SDK;

require 'vendor/autoload.php';

use Kopokopo\SDK\Requests\SettlementAccountRequest;
use Kopokopo\SDK\Requests\SettleFundsRequest;
use Kopokopo\SDK\Requests\StatusRequest;
use Exception;

class TransferService extends Service
{
    public function createSettlementAccount($options)
    {
        $settlementAccountRequest = new SettlementAccountRequest($options);
        try {
            $response = $this->client->post('merchant_bank_accounts', ['body' => json_encode($settlementAccountRequest->getSettlementAccountBody()), 'headers' => $settlementAccountRequest->getHeaders()]);

            return $this->success($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function settleFunds($options)
    {
        $settleFundsRequest = new SettleFundsRequest($options);
        try {
            $response = $this->client->post('transfers', ['body' => json_encode($settleFundsRequest->getSettleFundsBody()), 'headers' => $settleFundsRequest->getHeaders()]);

            return $this->success($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function settlementStatus($options)
    {
        $settlementStatus = new StatusRequest($options);
        try {
            $response = $this->client->get('transfer_status', ['query' => $settlementStatus->getLocation(), 'headers' => $settlementStatus->getHeaders()]);

            return $this->success($response);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
