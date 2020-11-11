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
use Kopokopo\SDK\StkService;

class StkServiceTest extends TestCase
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
        *    initiateIncomingPayment() setup
        */

        // initiateIncomingPayment() response headers
        $incomingPaymentRequestHeaders = file_get_contents(__DIR__.'/Mocks/incomingPaymentHeaders.json');

        // Create an instance of MockHandler for returning responses for initiateIncomingPayment()
        $incomingPaymentRequestMock = new MockHandler([
            new Response(200, json_decode($incomingPaymentRequestHeaders, true)),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $incomingPaymentRequestHandler = HandlerStack::create($incomingPaymentRequestMock);

        // Create a new instance of client using the initiateIncomingPayment() handler
        $incomingPaymentRequestClient = new Client(['handler' => $incomingPaymentRequestHandler]);

        // Use $incomingPaymentRequestClient to create an instance of the StkService() class
        $this->incomingPaymentRequestClient = new StkService($incomingPaymentRequestClient, $options);

        /*
        *    incomingPaymentRequestStatus() setup
        */

        // json response to be returned
        $statusBody = file_get_contents(__DIR__.'/Mocks/stk-status.json');

        // Create an instance of MockHandler for returning responses for incomingPaymentRequestStatus()
        $statusMock = new MockHandler([
            new Response(200, [], $statusBody),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $statusHandler = HandlerStack::create($statusMock);

        // Create a new instance of client using the incomingPaymentRequestStatus() handler
        $statusClient = new Client(['handler' => $statusHandler]);

        // Use the $statusClient to create an instance of the StkService() class
        $this->statusClient = new StkService($statusClient, $options);
    }

    /*
    *   Payment Request tests
    */

    public function testIncomingPaymentRequestSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->incomingPaymentRequestClient->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA',
                'shortCode' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phoneNumber' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testIncomingPaymentRequestWithNoFirstNameFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the firstName'],
            $this->incomingPaymentRequestClient->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA',
                'shortCode' => '13432',
                'lastName' => 'Doe',
                'phoneNumber' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testIncomingPaymentRequestWithNoLastNameFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the lastName'],
            $this->incomingPaymentRequestClient->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA',
                'shortCode' => '13432',
                'firstName' => 'Jane',
                'phoneNumber' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testIncomingPaymentRequestWithNoPhoneFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the phoneNumber'],
            $this->incomingPaymentRequestClient->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA',
                'shortCode' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testIncomingPaymentRequestWithInvalidPhoneFormatFails()
    {
        $this->assertArraySubset(
            ['data' => 'Invalid phone number format'],
            $this->incomingPaymentRequestClient->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA',
                'shortCode' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phoneNumber' => '0712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testIncomingPaymentRequestWithNoEmailSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->incomingPaymentRequestClient->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA',
                'shortCode' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phoneNumber' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testIncomingPaymentRequestWithNoTillFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the shortCode'],
            $this->incomingPaymentRequestClient->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phoneNumber' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testIncomingPaymentRequestWithNoCallbackUrlFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the callbackUrl'],
            $this->incomingPaymentRequestClient->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA',
                'shortCode' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phoneNumber' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testIncomingPaymentRequestWithoutEmailSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->incomingPaymentRequestClient->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA',
                'shortCode' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phoneNumber' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testIncomingPaymentRequestWithNoCurrencyFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the currency'],
            $this->incomingPaymentRequestClient->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA',
                'shortCode' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phoneNumber' => '+254712345678',
                'amount' => 3455,
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testIncomingPaymentRequestWithMetadataSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->incomingPaymentRequestClient->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA',
                'shortCode' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phoneNumber' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'metadata' => [
                    'customer_id' => '123456789',
                    'reference' => '123456',
                    'notes' => 'Payment for invoice 12345',
                ],
            ])
        );
    }

    public function testIncomingPaymentRequestWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->incomingPaymentRequestClient->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA',
                'shortCode' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phoneNumber' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
            ])
        );
    }

    /*
    *   Payment Request status tests
    */

    public function testIncomingPaymentRequestStatusSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->statusClient->incomingPaymentRequestStatus([
                'location' => 'my_request_id',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testIncomingPaymentRequestStatusWithNoLocationFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the location'],
            $this->statusClient->incomingPaymentRequestStatus([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testIncomingPaymentRequestStatusWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->statusClient->incomingPaymentRequestStatus([
                'location' => 'my_request_id',
            ])
        );
    }
}
