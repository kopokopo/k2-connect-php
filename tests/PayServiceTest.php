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

class PayServiceTest extends TestCase
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
        $this->payRecipientClient = new PayService($payRecipientClient, $options);

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
        $this->sendPayClient = new PayService($sendPayClient, $options);

        /*
        *    getStatus() setup
        */

        // json response to be returned
        $statusBody = file_get_contents(__DIR__.'/Mocks/payStatus.json');

        // Create an instance of MockHandler for returning responses for getStatus()
        $statusMock = new MockHandler([
            new Response(200, [], $statusBody),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $statusHandler = HandlerStack::create($statusMock);

        // Create a new instance of client using the getStatus() handler
        $statusClient = new Client(['handler' => $statusHandler]);

        // Use $statusClient to create an instance of the PayService() class
        $this->statusClient = new PayService($statusClient, $options);
    }

    /*
    *   Add Pay Recipient (Mobile) tests
    */

    public function testAddPayRecipientMobileSucceeds()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'mobile_wallet',
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'phoneNumber' => '+254712345678',
            'network' => 'safaricom',
            'email' => 'example@example.com',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testAddPayRecipientMobileWithNoFirstNameFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'mobile_wallet',
            'lastName' => 'Doe',
            'phoneNumber' => '+254712345678',
            'network' => 'safaricom',
            'email' => 'example@example.com',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the firstName', $response['data']);
    }

    public function testAddPayRecipientMobileWithNoLastNameFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'mobile_wallet',
            'firstName' => 'Jane',
            'phoneNumber' => '+254712345678',
            'network' => 'safaricom',
            'email' => 'example@example.com',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the lastName', $response['data']);
    }

    public function testAddPayRecipientMobileWithNoPhoneFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'mobile_wallet',
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'network' => 'safaricom',
            'email' => 'example@example.com',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the phoneNumber', $response['data']);
    }

    public function testAddPayRecipientMobileWithInvalidPhoneFormatFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'mobile_wallet',
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'phoneNumber' => '0712345678',
            'network' => 'safaricom',
            'email' => 'example@example.com',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('Invalid phone number format', $response['data']);
    }

    public function testAddPayRecipientMobileWithNoNetworkFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'mobile_wallet',
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'phoneNumber' => '+254712345678',
            'email' => 'example@example.com',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the network', $response['data']);
    }

    public function testAddPayRecipientMobileWithNoEmailSucceeds()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'mobile_wallet',
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'phoneNumber' => '+254712345678',
            'network' => 'safaricom',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testAddPayRecipientMobileWithNoAccessTokenFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'mobile_wallet',
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'phoneNumber' => '+254712345678',
            'network' => 'safaricom',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }

    /*
    *   Add Pay Recipient (Bank Account) tests
    */

    public function testAddPayRecipientAccountSucceeds()
    {
       $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'bank_account',
            'accountName' => 'Doe',
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'accountNumber' => '1234567890',
            'settlementMethod' => 'EFT',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testAddPayRecipientAccountWithNoAccountNameFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'bank_account',
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'accountNumber' => '1234567890',
            'settlementMethod' => 'EFT',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accountName', $response['data']);
    }

    public function testAddPayRecipientAccountWithNoBankBranchRefFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'bank_account',
            'accountName' => 'Doe',
            'accountNumber' => '1234567890',
            'settlementMethod' => 'EFT',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the bankBranchRef', $response['data']);
    }

    public function testAddPayRecipientAccountWithNoAccountNumberFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'bank_account',
            'accountName' => 'Doe',
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'settlementMethod' => 'EFT',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accountNumber', $response['data']);
    }

    public function testAddPayRecipientAccountWithNoSettlementMethodFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'bank_account',
            'accountName' => 'Doe',
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'accountNumber' => '1234567890',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the settlementMethod', $response['data']);
    }

    public function testAddPayRecipientAccountWithNoAccessTokenFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'bank_account',
            'accountName' => 'Doe',
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'accountNumber' => '1234567890',
            'settlementMethod' => 'EFT',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }


    /*
    *   Add Pay Recipient (External Till) tests
    */

    public function testAddPayRecipientTillSucceeds()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'till',
            'tillName' => 'Doe',
            'tillNumber' => '123456',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testAddPayRecipientTillWithNoTillNameFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'till',
            'tillNumber' => '123456',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the tillName', $response['data']);
    }

    public function testAddPayRecipientTillWithNoTillNumberFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'till',
            'tillName' => 'Doe',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the tillNumber', $response['data']);
    }

    public function testAddPayRecipientTillWithNoAccessTokenFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'till',
            'tillName' => 'Doe',
            'tillNumber' => '123456',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }

    /*
    *   Add Pay Recipient (Paybill) tests
    */

    public function testAddPayRecipientPaybillSucceeds()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'paybill',
            'paybillName' => 'Doe',
            'paybillNumber' => '123456',
            'paybillAccountNumber' => '67890',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testAddPayRecipientPaybillWithNoPaybillNameFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'paybill',
            'paybillNumber' => '123456',
            'paybillAccountNumber' => '67890',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the paybillName', $response['data']);
    }

    public function testAddPayRecipientPaybillWithNoPaybillNumberFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'paybill',
            'paybillName' => 'Doe',
            'paybillAccountNumber' => '67890',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the paybillNumber', $response['data']);
    }

    public function testAddPayRecipientPaybillWithNoPaybillAccountNumberFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'paybill',
            'paybillName' => 'Doe',
            'paybillNumber' => '123456',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the paybillAccountNumber', $response['data']);
    }

    public function testAddPayRecipientPaybillWithNoAccessTokenFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'type' => 'paybill',
            'paybillName' => 'Doe',
            'paybillNumber' => '123456',
            'paybillAccountNumber' => '67890',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }

    /*
    *   Add Pay Recipient tests
    */

    public function testAddPayRecipientWithNoTypeFails()
    {
        $response = $this->payRecipientClient->addPayRecipient([
            'accountName' => 'Doe',
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'accountNumber' => '1234567890',
            'phoneNumber' => '+254712345678',
            'email' => 'example@example.com',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the type', $response['data']);
    }

    /*
    *   Send Pay tests
    */

    public function testSendPaySucceeds()
    {
        $response = $this->sendPayClient->sendPay([
            'destinationReference' => 'my_destination_alias',
            'destinationType' => 'mobile_wallet',
            'amount' => 3444,
            'currency' => 'KES',
            'description' => 'Salary payment for May 2021',
            'category' => 'Salary Payment',
            'tags' => 'Salary,May',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
            'callbackUrl' => 'http://localhost:8000/webhook',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testSendPayWithNoDestinationReferenceFails()
    {
        $response = $this->sendPayClient->sendPay([
            'destinationType' => 'mobile_wallet',
            'amount' => 3444,
            'currency' => 'KES',
            'description' => 'Salary payment for May 2021',
            'category' => 'Salary Payment',
            'tags' => 'Salary,May',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
            'callbackUrl' => 'http://localhost:8000/webhook',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the destinationReference', $response['data']);
    }

    public function testSendPayWithNoAmountFails()
    {
        $response = $this->sendPayClient->sendPay([
            'destinationReference' => 'my_destination_alias',
            'destinationType' => 'mobile_wallet',
            'currency' => 'KES',
            'description' => 'Salary payment for May 2021',
            'category' => 'Salary Payment',
            'tags' => 'Salary,May',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
            'callbackUrl' => 'http://localhost:8000/webhook',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the amount', $response['data']);
    }

    public function testSendPayWithNoCurrencyFails()
    {
        $response = $this->sendPayClient->sendPay([
            'destinationReference' => 'my_destination_alias',
            'destinationType' => 'mobile_wallet',
            'amount' => 3444,
            'description' => 'Salary payment for May 2021',
            'category' => 'Salary Payment',
            'tags' => 'Salary,May',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
            'callbackUrl' => 'http://localhost:8000/webhook',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the currency', $response['data']);
    }

    public function testSendPayWithNoDescriptionFails()
    {
        $response = $this->sendPayClient->sendPay([
            'destinationReference' => 'my_destination_alias',
            'destinationType' => 'mobile_wallet',
            'amount' => 3444,
            'currency' => 'KES',
            'category' => 'Salary Payment',
            'tags' => 'Salary,May',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
            'callbackUrl' => 'http://localhost:8000/webhook',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the description', $response['data']);
    }

    public function testSendPayWithNoCallbackUrlFails()
    {
        $response = $this->sendPayClient->sendPay([
            'destinationReference' => 'my_destination_alias',
            'destinationType' => 'mobile_wallet',
            'amount' => 3444,
            'currency' => 'KES',
            'description' => 'Salary payment for May 2021',
            'category' => 'Salary Payment',
            'tags' => 'Salary,May',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the callbackUrl', $response['data']);
    }

    public function testSendPayWithNoAccessTokenFails()
    {
        $response = $this->sendPayClient->sendPay([
            'destinationReference' => 'my_destination_alias',
            'destinationType' => 'mobile_wallet',
            'amount' => 3444,
            'currency' => 'KES',
            'description' => 'Salary payment for May 2021',
            'category' => 'Salary Payment',
            'tags' => 'Salary,May',
            'callbackUrl' => 'http://localhost:8000/webhook',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }

    /*
    *  Pay status tests
    */

    public function testGetStatus()
    {
        $response = $this->statusClient->getStatus([
            'location' => 'http://localhost:3000/payments/c7f300c0-f1ef-4151-9bbe-005005aa3747',
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
            'location' => 'http://localhost:3000/payments/c7f300c0-f1ef-4151-9bbe-005005aa3747',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }
}
