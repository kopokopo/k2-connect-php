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

class StkTest extends TestCase
{
    public function setup()
    {
        $this->clientId = 'your_client_id';
        $this->clientSecret = '10af7ad062a21d9c841877f87b7dec3dbe51aeb3';

        /*
        *    paymentRequest() setup
        */

        // paymentRequest() response headers
        $paymentRequestHeaders = file_get_contents(__DIR__.'/Mocks/paymentRequestHeaders.json');

        // Create an instance of MockHandler for returning responses for paymentRequest()
        $paymentRequestMock = new MockHandler([
            new Response(200, json_decode($paymentRequestHeaders, true)),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $paymentRequestHandler = HandlerStack::create($paymentRequestMock);

        // Create a new instance of client using the paymentRequest() handler
        $paymentRequestClient = new Client(['handler' => $paymentRequestHandler]);

        // Use $paymentRequestClient to create an instance of the StkService() class
        $this->paymentRequestClient = new StkService($paymentRequestClient, $this->clientId, $this->clientSecret);

        /*
        *    paymentRequestStatus() setup
        */

        // json response to be returned
        $statusBody = file_get_contents(__DIR__.'/Mocks/stk-status.json');

        // Create an instance of MockHandler for returning responses for paymentRequestStatus()
        $statusMock = new MockHandler([
            new Response(200, [], $statusBody),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $statusHandler = HandlerStack::create($statusMock);

        // Create a new instance of client using the paymentRequestStatus() handler
        $statusClient = new Client(['handler' => $statusHandler]);

        // Use the $statusClient to create an instance of the StkService() class
        $this->statusClient = new StkService($statusClient, $this->clientId, $this->clientSecret);
    }

    /*
    *   Payment Request tests
    */

    public function testPaymentRequestSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->paymentRequestClient->paymentRequest([
                'paymentChannel' => 'M-PESA',
                'tillNumber' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPaymentRequestWithNoFirstNameFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the firstName'],
            $this->paymentRequestClient->paymentRequest([
                'paymentChannel' => 'M-PESA',
                'tillNumber' => '13432',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPaymentRequestWithNoLastNameFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the lastName'],
            $this->paymentRequestClient->paymentRequest([
                'paymentChannel' => 'M-PESA',
                'tillNumber' => '13432',
                'firstName' => 'Jane',
                'phone' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPaymentRequestWithNoPhoneFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the phone'],
            $this->paymentRequestClient->paymentRequest([
                'paymentChannel' => 'M-PESA',
                'tillNumber' => '13432',
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

    public function testPaymentRequestWithInvalidPhoneFormatFails()
    {
        $this->assertArraySubset(
            ['data' => 'Invalid phone format'],
            $this->paymentRequestClient->paymentRequest([
                'paymentChannel' => 'M-PESA',
                'tillNumber' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '0712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPaymentRequestWithNoEmailSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->paymentRequestClient->paymentRequest([
                'paymentChannel' => 'M-PESA',
                'tillNumber' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPaymentRequestWithNoTillFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the tillNumber'],
            $this->paymentRequestClient->paymentRequest([
                'paymentChannel' => 'M-PESA',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPaymentRequestWithNoCallbackUrlFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the callbackUrl'],
            $this->paymentRequestClient->paymentRequest([
                'paymentChannel' => 'M-PESA',
                'tillNumber' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPaymentRequestWithoutEmailSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->paymentRequestClient->paymentRequest([
                'paymentChannel' => 'M-PESA',
                'tillNumber' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'amount' => 3455,
                'currency' => 'KES',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPaymentRequestWithNoCurrencyFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the currency'],
            $this->paymentRequestClient->paymentRequest([
                'paymentChannel' => 'M-PESA',
                'tillNumber' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'amount' => 3455,
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPaymentRequestWithMetadataSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->paymentRequestClient->paymentRequest([
                'paymentChannel' => 'M-PESA',
                'tillNumber' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
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

    public function testPaymentRequestWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->paymentRequestClient->paymentRequest([
                'paymentChannel' => 'M-PESA',
                'tillNumber' => '13432',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
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

    public function testPaymentRequestStatusSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->statusClient->paymentRequestStatus([
                'location' => 'my_request_id',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPaymentRequestStatusWithNoLocationFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the location'],
            $this->statusClient->paymentRequestStatus([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPaymentRequestStatusWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->statusClient->paymentRequestStatus([
                'location' => 'my_request_id',
            ])
        );
    }
}
