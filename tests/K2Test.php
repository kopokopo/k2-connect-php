<?php

namespace Kopokopo\SDK\Tests;

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Kopokopo\SDK\K2;

class K2Test extends TestCase
{
    public function setup()
    {
        $this->clientId = 'your_client_id';
        $this->clientSecret = '10af7ad062a21d9c841877f87b7dec3dbe51aeb3';
        $this->baseUrl = 'https://9284bede-3488-4b2b-a1e8-d6e9f8d86aff.mock.pstmn.io';

        $this->client = new K2($this->clientId, $this->clientSecret, $this->baseUrl);
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
        $this->assertInstanceOf(\Kopokopo\SDK\TransferService::class, $this->client->TransferService());
    }
}
