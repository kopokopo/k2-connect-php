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
    public function setup()
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
        $statusBody = file_get_contents(__DIR__.'/Mocks/transaction-sms-notification-status.json');

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
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->transactionSmsNotificationClient->sendTransactionSmsNotification([
                'message' => 'Your message here',
                'webhookEventReference' => '2133dbfb-24b9-40fc-ae57-2d7559785760',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testTransactionSmsNotificationWithNoMessage()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the message'],
            $this->transactionSmsNotificationClient->sendTransactionSmsNotification([
                'webhookEventReference' => '2133dbfb-24b9-40fc-ae57-2d7559785760',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testTransactionSmsNotificationWithNoWebhookEventReference()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the webhookEventReference'],
            $this->transactionSmsNotificationClient->sendTransactionSmsNotification([
                'message' => 'Your message here',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testTransactionSmsNotificationWithNoCallbackUrlFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the callbackUrl'],
            $this->transactionSmsNotificationClient->sendTransactionSmsNotification([
                'message' => 'Your message here',
                'webhookEventReference' => '2133dbfb-24b9-40fc-ae57-2d7559785760',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testTransactionSmsNotificationWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->transactionSmsNotificationClient->sendTransactionSmsNotification([
                'message' => 'Your message here',
                'webhookEventReference' => '2133dbfb-24b9-40fc-ae57-2d7559785760',
                'callbackUrl' => 'http://localhost:8000/test',
            ])
        );
    }

    /*
    *   Transaction Sms Notification Request status tests
    */

    public function testGetStatusSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->statusClient->getStatus([
                'location' => 'http://localhost:3000/transaction_sms_notifications/9dfd9772-ce1c-4104-a12f-ca550d1b2bdf',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testGetStatusWithNoLocationFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the location'],
            $this->statusClient->getStatus([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testGetStatusWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->statusClient->getStatus([
                'location' => 'http://localhost:3000/transaction_sms_notifications/9dfd9772-ce1c-4104-a12f-ca550d1b2bdf',
            ])
        );
    }
}
