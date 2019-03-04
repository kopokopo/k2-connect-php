<?php

namespace Kopokopo\SDK\Tests;

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Kopokopo\SDK\K2;

class K2Test extends TestCase
{
    public function setup()
    {
        $this->client_id = 'your_client_id';
        $this->client_secret = '10af7ad062a21d9c841877f87b7dec3dbe51aeb3';

        $this->client = new K2($this->client_id, $this->client_secret);
    }

    public function testTokenServiceClass()
    {
        $this->assertInstanceOf(\Kopokopo\SDK\TokenService::class, $this->client->TokenService());
    }

    public function testWebhooksClass()
    {
        $this->assertInstanceOf(\Kopokopo\SDK\Webhooks::class, $this->client->Webhooks());
    }

    public function testStkServiceClass()
    {
        $this->assertInstanceOf(\Kopokopo\SDK\StkService::class, $this->client->StkService());
    }

    public function testPayServiceClass()
    {
        $this->assertInstanceOf(\Kopokopo\SDK\PayService::class, $this->client->PayService());
    }

    public function testTransferServiceClass()
    {
        $this->assertInstanceOf(\Kopokopo\SDK\TransferService::class, $this->client->TransferService());
    }
}
