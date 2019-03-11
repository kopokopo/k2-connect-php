<?php

namespace Kopokopo\SDK\Tests;

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Kopokopo\SDK\K2;

class WebhookTest extends TestCase
{
    public function setup()
    {
        $this->clientId = 'your_client_id';
        $this->clientSecret = '10af7ad062a21d9c841877f87b7dec3dbe51aeb3';

        $k2 = new K2($this->clientId, $this->clientSecret);
        $this->client = $k2->Webhooks();
    }

    /*
    *   Webhook subscribe tests
    */

    public function testWebhookSubscribeSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->subscribe([
                'eventType' => 'buy_goods_received',
                'url' => 'http://localhost:8000/webhook',
                'webhookSecret' => 'my_webhook_secret',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testWebhookSubscribeWithNoEventTypeFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the eventType'],
            $this->client->subscribe([
                'url' => 'http://localhost:8000/webhook',
                'webhookSecret' => 'my_webhook_secret',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testWebhookSubscribeWithNoUrlFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the url'],
            $this->client->subscribe([
                'eventType' => 'buy_goods_received',
                'webhookSecret' => 'my_webhook_secret',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testWebhookSubscribeWithNoWebhookSecretFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the webhookSecret'],
            $this->client->subscribe([
                'eventType' => 'buy_goods_received',
                'url' => 'http://localhost:8000/webhook',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testWebhookSubscribeWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->client->subscribe([
                'eventType' => 'buy_goods_received',
                'url' => 'http://localhost:8000/webhook',
                'webhookSecret' => 'my_webhook_secret',
            ])
        );
    }

    /*
    *   Webhook handler tests
    */

    /**
     * @expectedException \ArgumentCountError
     */
    public function testWebhookHandlerWithNoDataFails()
    {
        $this->expectException($this->client->webhookHandler());
    }

    // public function testAuth()
    // {
    //     $k2Sig = '2fa35c36b207fccef1e9d95608c2abdb6702151cab525e74a69426d686dedf30';
    //     // $k2Secret = "10af7ad062a21d9c841877f87b7dec3dbe51aeb3";/
    //     $reqBody = [
    //         'id' => 'cac95329-9fa5-42f1-a4fc-c08af7b868fb',
    //         'resourceId' => 'cdb5f11f-62df-e611-80ee-0aa34a9b2388',
    //         'topic' => 'customer_created',
    //         'created_at' => '2017-01-20T22:45:12.790Z',
    //         'event' => [
    //             'type' => 'Customer Created',
    //             'resource' => [
    //                 'first_name' => 'Nicollet',
    //                 'middle_name' => 'N',
    //                 'last_name' => 'Njora',
    //                 'msisdn' => '+25472589598'
    //             ]
    //         ],
    //         '_links' => [
    //             'self' => 'https://api-sandbox.kopokopo.com/events/cac95329-9fa5-42f1-a4fc-c08af7b868fb',
    //             'resource' => 'https://api-sandbox.kopokopo.com/customers/cdb5f11f-62df-e611-80ee-0aa34a9b2388'
    //         ]
    //     ];

    //     print_r(json_encode($reqBody, JSON_FORCE_OBJECT));
    //     $this->assertArraySubset(
    //         ['status' => 'success'],
    //         $this->client->webhookHandler(json_encode($reqBody, JSON_FORCE_OBJECT), $k2Sig)
    //     );
    // }
}
