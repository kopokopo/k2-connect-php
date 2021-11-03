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
    public function setup()
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
        $statusBody = file_get_contents(__DIR__.'/Mocks/pay-status.json');

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
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'mobile_wallet',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phoneNumber' => '+254712345678',
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
                'phoneNumber' => '+254712345678',
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
                'phoneNumber' => '+254712345678',
                'network' => 'safaricom',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientMobileWithNoPhoneFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the phoneNumber'],
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
            ['data' => 'Invalid phone number format'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'mobile_wallet',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phoneNumber' => '0712345678',
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
                'phoneNumber' => '+254712345678',
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
                'phoneNumber' => '+254712345678',
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
                'phoneNumber' => '+254712345678',
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
                'accountName' => 'Doe',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'settlementMethod' => 'EFT',
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
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'settlementMethod' => 'EFT',
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
                'accountName' => 'Doe',
                'accountNumber' => '1234567890',
                'settlementMethod' => 'EFT',
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
                'accountName' => 'Doe',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'settlementMethod' => 'EFT',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientAccountWithNoSettlementMethodFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the settlementMethod'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'bank_account',
                'accountName' => 'Doe',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
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
                'accountName' => 'Doe',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'settlementMethod' => 'EFT',
            ])
        );
    }


    /*
    *   Add Pay Recipient (External Till) tests
    */

    public function testAddPayRecipientTillSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'till',
                'tillName' => 'Doe',
                'tillNumber' => '123456',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientTillWithNoTillNameFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the tillName'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'till',
                'tillNumber' => '123456',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientTillWithNoTillNumberFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the tillNumber'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'till',
                'tillName' => 'Doe',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientTillWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'till',
                'tillName' => 'Doe',
                'tillNumber' => '123456',
            ])
        );
    }

    /*
    *   Add Pay Recipient (Paybill) tests
    */

    public function testAddPayRecipientPaybillSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'paybill',
                'paybillName' => 'Doe',
                'paybillNumber' => '123456',
                'paybillAccountNumber' => '67890',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientPaybillWithNoPaybillNameFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the paybillName'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'paybill',
                'paybillNumber' => '123456',
                'paybillAccountNumber' => '67890',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientPaybillWithNoPaybillNumberFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the paybillNumber'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'paybill',
                'paybillName' => 'Doe',
                'paybillAccountNumber' => '67890',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientPaybillWithNoPaybillAccountNumberFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the paybillAccountNumber'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'paybill',
                'paybillName' => 'Doe',
                'paybillNumber' => '123456',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientPaybillWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->payRecipientClient->addPayRecipient([
                'type' => 'paybill',
                'paybillName' => 'Doe',
                'paybillNumber' => '123456',
                'paybillAccountNumber' => '67890',
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
                'accountName' => 'Doe',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'phoneNumber' => '+254712345678',
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
                'destinationReference' => 'my_destination_alias',
                'destinationType' => 'mobile_wallet',
                'amount' => 3444,
                'currency' => 'KES',
                'description' => 'Salary payment for May 2021',
			    'category' => 'Salary Payment',
			    'tags' => 'Salary,May',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'callbackUrl' => 'http://localhost:8000/webhook',
            ])
        );
    }

    public function testSendPayWithNoDestinationReferenceFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the destinationReference'],
            $this->sendPayClient->sendPay([
                'destinationType' => 'mobile_wallet',
                'amount' => 3444,
                'currency' => 'KES',
                'description' => 'Salary payment for May 2021',
			    'category' => 'Salary Payment',
			    'tags' => 'Salary,May',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'callbackUrl' => 'http://localhost:8000/webhook',
            ])
        );
    }

    public function testSendPayWithNoAmountFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the amount'],
            $this->sendPayClient->sendPay([
                'destinationReference' => 'my_destination_alias',
                'destinationType' => 'mobile_wallet',
                'currency' => 'KES',
                'description' => 'Salary payment for May 2021',
			    'category' => 'Salary Payment',
			    'tags' => 'Salary,May',
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
                'destinationReference' => 'my_destination_alias',
                'destinationType' => 'mobile_wallet',
                'amount' => 3444,
                'description' => 'Salary payment for May 2021',
			    'category' => 'Salary Payment',
			    'tags' => 'Salary,May',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'callbackUrl' => 'http://localhost:8000/webhook',
            ])
        );
    }

    public function testSendPayWithNoDescriptionFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the description'],
            $this->sendPayClient->sendPay([
                'destinationReference' => 'my_destination_alias',
                'destinationType' => 'mobile_wallet',
                'amount' => 3444,
                'currency' => 'KES',
			    'category' => 'Salary Payment',
			    'tags' => 'Salary,May',
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
                'destinationReference' => 'my_destination_alias',
                'destinationType' => 'mobile_wallet',
                'amount' => 3444,
                'currency' => 'KES',
                'description' => 'Salary payment for May 2021',
			    'category' => 'Salary Payment',
			    'tags' => 'Salary,May',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testSendPayWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->sendPayClient->sendPay([
                'destinationReference' => 'my_destination_alias',
                'destinationType' => 'mobile_wallet',
                'amount' => 3444,
                'currency' => 'KES',
                'description' => 'Salary payment for May 2021',
			    'category' => 'Salary Payment',
			    'tags' => 'Salary,May',
                'callbackUrl' => 'http://localhost:8000/webhook',
            ])
        );
    }

    /*
    *  Pay status tests
    */

    public function testGetStatus()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->statusClient->getStatus([
                'location' => 'http://localhost:3000/payments/c7f300c0-f1ef-4151-9bbe-005005aa3747',
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
                'location' => 'http://localhost:3000/payments/c7f300c0-f1ef-4151-9bbe-005005aa3747',
            ])
        );
    }
}
