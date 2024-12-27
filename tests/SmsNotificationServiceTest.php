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
use Kopokopo\SDK\SmsNotificationService;

class SmsNotificationServiceTest extends TestCase
{
    public function setup(): void
    {
        $options = [
            'clientId' => 'your_client_id',
            'clientSecret' => 'your_client_secret',
            'apiKey' => 'your_api_key',
            'baseUrl' => 'https://9284bede-d6e9f8d86aff.mock.pstmn.io'
        ];

        /*
        *    sendTransactionSmsNotification() setup
        */

        // sendTransactionSmsNotification() response headers
        $smsNotificationRequestHeaders = file_get_contents(__DIR__.'/Mocks/smsNotificationHeaders.json');

        // Create an instance of MockHandler for returning responses for sendTransactionSmsNotification()
        $smsNotificationRequestMock = new MockHandler([
            new Response(200, json_decode($smsNotificationRequestHeaders, true)),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $smsNotificationRequestHandler = HandlerStack::create($smsNotificationRequestMock);

        // Create a new instance of client using the sendTransactionSmsNotification() handler
        $transactionSmsNotificationClient = new Client(['handler' => $smsNotificationRequestHandler]);

        // Use $transactionSmsNotificationClient to create an instance of the SmsNotificationService() class
        $this->transactionSmsNotificationClient = new SmsNotificationService($transactionSmsNotificationClient, $options);

        /*
        *    getStatus() setup
        */

        // json response to be returned
        $statusBody = file_get_contents(__DIR__.'/Mocks/transactionSmsNotificationStatus.json');

        // Create an instance of MockHandler for returning responses for getStatus()
        $statusMock = new MockHandler([
            new Response(200, [], $statusBody),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $statusHandler = HandlerStack::create($statusMock);

        // Create a new instance of client using the getStatus() handler
        $statusClient = new Client(['handler' => $statusHandler]);

        // Use the $statusClient to create an instance of the SmsNotificationService() class
        $this->statusClient = new SmsNotificationService($statusClient, $options);
    }

    /*
    *   Transaction Sms Notification Request tests
    */

    public function testTransactionSmsNotificationSucceeds()
    {
        $response = $this->transactionSmsNotificationClient->sendTransactionSmsNotification([
            'message' => 'Your message here',
            'webhookEventReference' => '2133dbfb-24b9-40fc-ae57-2d7559785760',
            'callbackUrl' => 'http://localhost:8000/test',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testTransactionSmsNotificationWithNoMessage()
    {
        $response = $this->transactionSmsNotificationClient->sendTransactionSmsNotification([
            'webhookEventReference' => '2133dbfb-24b9-40fc-ae57-2d7559785760',
            'callbackUrl' => 'http://localhost:8000/test',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the message', $response['data']);
    }

    public function testTransactionSmsNotificationWithNoWebhookEventReference()
    {
        $response = $this->transactionSmsNotificationClient->sendTransactionSmsNotification([
            'message' => 'Your message here',
            'callbackUrl' => 'http://localhost:8000/test',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the webhookEventReference', $response['data']);
    }

    public function testTransactionSmsNotificationWithNoCallbackUrlFails()
    {
        $response = $this->transactionSmsNotificationClient->sendTransactionSmsNotification([
            'message' => 'Your message here',
            'webhookEventReference' => '2133dbfb-24b9-40fc-ae57-2d7559785760',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the callbackUrl', $response['data']);
    }

    public function testTransactionSmsNotificationWithNoAccessTokenFails()
    {
        $response = $this->transactionSmsNotificationClient->sendTransactionSmsNotification([
            'message' => 'Your message here',
            'webhookEventReference' => '2133dbfb-24b9-40fc-ae57-2d7559785760',
            'callbackUrl' => 'http://localhost:8000/test',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }

    /*
    *   Transaction Sms Notification Request status tests
    */

    public function testGetStatusSucceeds()
    {
        $response = $this->statusClient->getStatus([
            'location' => 'http://localhost:3000/transaction_sms_notifications/9dfd9772-ce1c-4104-a12f-ca550d1b2bdf',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testGetStatusWithNoLocationFails()
    {
        $response = $this->statusClient->getStatus([
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the location', $response['data']);
    }

    public function testGetStatusWithNoAccessTokenFails()
    {
        $response = $this->statusClient->getStatus([
            'location' => 'http://localhost:3000/transaction_sms_notifications/9dfd9772-ce1c-4104-a12f-ca550d1b2bdf',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }
}
