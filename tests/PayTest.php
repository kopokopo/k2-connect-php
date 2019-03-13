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
use Kopokopo\SDK\PayService;

class PayTest extends TestCase
{
    public function setup()
    {
        $this->clientId = 'your_client_id';
        $this->clientSecret = '10af7ad062a21d9c841877f87b7dec3dbe51aeb3';

        /*
        *    addPayRecipient() setup
        */

        // Headers to be returned by the addPayRecipient() mock
        $payRecipientHeaders = file_get_contents(__DIR__.'/Mocks/payRecipientHeaders.json');

        // Create an instance of MockHandler for returning responses for addPayRecipient()
        $payRecipientMock = new MockHandler([
            new Response(200, json_decode($payRecipientHeaders, true)),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $payRecipientHandler = HandlerStack::create($payRecipientMock);

        // Create a new instance of client using the addPayRecipient() handler
        $payRecipientClient = new Client(['handler' => $payRecipientHandler]);

        // Use $payRecipientClient to create an instance of the PayService() class
        $this->payRecipientClient = new PayService($payRecipientClient, $this->clientId, $this->clientSecret);

        /*
        *    sendPay() setup
        */

        // Headers to be returned by the sendPay() mock
        $sendPayHeaders = file_get_contents(__DIR__.'/Mocks/sendPayHeaders.json');

        // Create an instance of MockHandler for returning responses for sendPay()
        $sendPayMock = new MockHandler([
            new Response(200, json_decode($sendPayHeaders, true)),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $sendPayHandler = HandlerStack::create($sendPayMock);

        // Create a new instance of client using the sendPay() handler
        $sendPayClient = new Client(['handler' => $sendPayHandler]);

        // Use $sendPayClient to create an instance of the PayService() class
        $this->sendPayClient = new PayService($sendPayClient, $this->clientId, $this->clientSecret);

        /*
        *    payStatus() setup
        */

        // json response to be returned
        $statusBody = file_get_contents(__DIR__.'/Mocks/pay-status.json');

        // Create an instance of MockHandler for returning responses for payStatus()
        $statusMock = new MockHandler([
            new Response(200, [], $statusBody),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $statusHandler = HandlerStack::create($statusMock);

        // Create a new instance of client using the payStatus() handler
        $statusClient = new Client(['handler' => $statusHandler]);

        // Use $statusClient to create an instance of the PayService() class
        $this->statusClient = new PayService($statusClient, $this->clientId, $this->clientSecret);
    }

    /*
    *   Add Pay Recipient (Mobile) tests
    */

    public function testAddPayRecipientMobileSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'mobile_wallet',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'network' => 'safaricom',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientMobileWithNoFirstNameFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the firstName'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'mobile_wallet',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'network' => 'safaricom',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientMobileWithNoLastNameFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the lastName'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'mobile_wallet',
                'firstName' => 'Jane',
                'phone' => '+254712345678',
                'network' => 'safaricom',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientMobileWithNoPhoneFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the phone'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'mobile_wallet',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'network' => 'safaricom',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientMobileWithInvalidPhoneFormatFails()
    {
        $this->assertArraySubset(
            ['data' => 'Invalid phone format'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'mobile_wallet',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '0712345678',
                'network' => 'safaricom',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientMobileWithNoNetworkFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the network'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'mobile_wallet',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientMobileWithNoEmailSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'mobile_wallet',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'network' => 'safaricom',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientMobileWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'mobile_wallet',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'network' => 'safaricom',
            ])
        );
    }

    /*
    *   Add Pay Recipient (Bank Account) tests
    */

    public function testAddPayRecipientAccountSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'bank_account',
                'name' => 'Jane',
                'accountName' => 'Doe',
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'phone' => '+254712345678',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientAccountWithNoAccountNameFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accountName'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'bank_account',
                'name' => 'Jane',
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'phone' => '+254712345678',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientAccountWithNoBankRefFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the bankRef'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'bank_account',
                'name' => 'Jane',
                'accountName' => 'Doe',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'phone' => '+254712345678',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientAccountWithNoBankBranchRefFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the bankBranchRef'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'bank_account',
                'name' => 'Jane',
                'accountName' => 'Doe',
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'phone' => '+254712345678',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientAccountWithNoAccountNumberFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accountNumber'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'bank_account',
                'name' => 'Jane',
                'accountName' => 'Doe',
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'phone' => '+254712345678',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientAccountWithNoPhoneFails()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'bank_account',
                'name' => 'Jane',
                'accountName' => 'Doe',
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientAccountWithInvalidPhoneFormatFails()
    {
        $this->assertArraySubset(
            ['data' => 'Invalid phone format'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'bank_account',
                'name' => 'Jane',
                'accountName' => 'Doe',
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'phone' => '0712345678',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientAccountWithNoEmailSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'bank_account',
                'name' => 'Jane',
                'accountName' => 'Doe',
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'phone' => '+254712345678',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientAccountWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'bank_account',
                'name' => 'Jane',
                'accountName' => 'Doe',
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'phone' => '+254712345678',
                'email' => 'example@example.com',
            ])
        );
    }

    /*
    *   Add Pay Recipient tests
    */

    public function testAddPayRecipientWithNoTypeFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the type'],
            $this->payRecipientClient->addPayRecipient([
                'name' => 'Jane',
                'accountName' => 'Doe',
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'phone' => '+254712345678',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    /*
    *   Send Pay tests
    */

    public function testSendPaySucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->sendPayClient->sendPay([
                'destination' => 'my_destination_alias',
                'amount' => 3444,
                'currency' => 'KES',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'callbackUrl' => 'http://localhost:8000/webhook',
            ])
        );
    }

    public function testSendPayWithNoDestinationFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the destination'],
            $this->sendPayClient->sendPay([
                'amount' => 3444,
                'currency' => 'KES',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'callbackUrl' => 'http://localhost:8000/webhook',
            ])
        );
    }

    public function testSendPayWithNoAmountFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the destination'],
            $this->sendPayClient->sendPay([
                'currency' => 'KES',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'callbackUrl' => 'http://localhost:8000/webhook',
            ])
        );
    }

    public function testSendPayWithNoCurrencyFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the currency'],
            $this->sendPayClient->sendPay([
                'destination' => 'my_destination_alias',
                'amount' => 3444,
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'callbackUrl' => 'http://localhost:8000/webhook',
            ])
        );
    }

    public function testSendPayWithNoCallbackUrlFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the callbackUrl'],
            $this->sendPayClient->sendPay([
                'destination' => 'my_destination_alias',
                'amount' => 3444,
                'currency' => 'KES',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testSendPayWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->sendPayClient->sendPay([
                'destination' => 'my_destination_alias',
                'amount' => 3444,
                'currency' => 'KES',
                'callbackUrl' => 'http://localhost:8000/webhook',
            ])
        );
    }

    /*
    *  Pay status tests
    */

    public function testPayStatus()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->statusClient->payStatus([
                'location' => 'my_request_id',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPayStatusWithNoLocationFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the location'],
            $this->statusClient->payStatus([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPayStatusWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->statusClient->payStatus([
                'location' => 'my_request_id',
            ])
        );
    }
}
