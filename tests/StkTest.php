<?php

namespace Kopokopo\SDK\Tests;

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Kopokopo\SDK\K2;

class StkTest extends TestCase
{
    public function setup()
    {
        $this->clientId = 'your_client_id';
        $this->clientSecret = '10af7ad062a21d9c841877f87b7dec3dbe51aeb3';

        $k2 = new K2($this->clientId, $this->clientSecret);
        $this->client = $k2->StkService();
    }

    /*
    *   Payment Request tests
    */

    public function testPaymentRequest()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->paymentRequest([
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

    public function testPaymentRequestWithNoFirstName()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the firstName'],
            $this->client->paymentRequest([
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

    public function testPaymentRequestWithNoLastName()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the lastName'],
            $this->client->paymentRequest([
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

    public function testPaymentRequestWithNoPhone()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the phone'],
            $this->client->paymentRequest([
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

    public function testPaymentRequestWithInvalidPhoneFormat()
    {
        $this->assertArraySubset(
            ['data' => 'Invalid phone format'],
            $this->client->paymentRequest([
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

    public function testPaymentRequestWithNoEmail()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->paymentRequest([
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

    public function testPaymentRequestWithNoTill()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the tillNumber'],
            $this->client->paymentRequest([
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

    public function testPaymentRequestWithNoCallbackUrl()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the callbackUrl'],
            $this->client->paymentRequest([
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

    public function testPaymentRequestWithoutEmail()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->paymentRequest([
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

    public function testPaymentRequestWithNoCurrency()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the currency'],
            $this->client->paymentRequest([
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

    public function testPaymentRequestWithMetadata()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->paymentRequest([
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

    public function testPaymentRequestWithNoAccessToken()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->client->paymentRequest([
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

    public function testPaymentRequestStatus()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->paymentRequestStatus([
                'location' => 'my_request_id',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPaymentRequestStatusWithNoLocation()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the location'],
            $this->client->paymentRequestStatus([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPaymentRequestStatusWithNoAccessToken()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->client->paymentRequestStatus([
                'location' => 'my_request_id',
            ])
        );
    }
}
