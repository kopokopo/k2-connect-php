<?php

namespace Kopokopo\SDK\Tests;

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Kopokopo\SDK\K2;

class K2Test extends TestCase
{
    public function setup()
    {
        $options = [
            'clientId' => 'your_client_id',
            'clientSecret' => 'your_client_secret',
            'apiKey' => 'your_api_key',
            'baseUrl' => 'https://9284bede-d6e9f8d86aff.mock.pstmn.io'
        ];

        $this->client = new K2($options);
    }

    public function testTokenServiceClassInitialised()
    {
        $this->assertInstanceOf(\Kopokopo\SDK\TokenService::class, $this->client->TokenService());
    }

    public function testWebhooksClassInitialised()
    {
        $this->assertInstanceOf(\Kopokopo\SDK\Webhooks::class, $this->client->Webhooks());
    }

    public function testStkServiceClassInitialised()
    {
        $this->assertInstanceOf(\Kopokopo\SDK\StkService::class, $this->client->StkService());
    }

    public function testPayServiceClassInitialised()
    {
        $this->assertInstanceOf(\Kopokopo\SDK\PayService::class, $this->client->PayService());
    }

    public function testTransferServiceClassInitialised()
    {
        $this->assertInstanceOf(\Kopokopo\SDK\SettlementTransferService::class, $this->client->SettlementTransferService());
    }
}
