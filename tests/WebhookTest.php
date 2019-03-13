<?php

namespace Kopokopo\SDK\Tests;

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use Kopokopo\SDK\K2;
use Kopokopo\SDK\Webhooks;

class WebhookTest extends TestCase
{
    public function setup()
    {
        $this->clientId = 'your_client_id';
        $this->clientSecret = '10af7ad062a21d9c841877f87b7dec3dbe51aeb3';

        $k2 = new K2($this->clientId, $this->clientSecret);
        $this->client = $k2->Webhooks();

        /*
        *    subscribe() setup
        */

        // subscribe() response headers
        $subscribeHeaders = file_get_contents(__DIR__.'/Mocks/subscribeHeaders.json');

        // Create an instance of MockHandler for returning responses for subscribe()
        $subscribeMock = new MockHandler([
            new Response(200, json_decode($subscribeHeaders, true)),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $subscribeHandler = HandlerStack::create($subscribeMock);

        // Create a new instance of client using the subscribe() handler
        $subscribeClient = new Client(['handler' => $subscribeHandler]);

        // Use $subscribeClient to create an instance of the Webhooks() class
        $this->subscribeClient = new Webhooks($subscribeClient, $this->clientId, $this->clientSecret);
    }

    /*
    *   Webhook subscribe tests
    */

    public function testWebhookSubscribeSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->subscribeClient->subscribe([
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
            $this->subscribeClient->subscribe([
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
            $this->subscribeClient->subscribe([
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
            $this->subscribeClient->subscribe([
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
            $this->subscribeClient->subscribe([
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

    //     $reqBody = file_get_contents(__DIR__.'/Mocks/webhook.json');

    //     $this->assertArraySubset(
    //         ['status' => 'success'],
    //         $this->client->webhookHandler(json_encode($reqBody), $k2Sig)
    //     );
    // }
}
