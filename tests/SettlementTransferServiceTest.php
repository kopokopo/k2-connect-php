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
use Kopokopo\SDK\SettlementTransferService;

class SettlementTransferServiceTest extends TestCase
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
        *    createMerchantBankAccount() setup
        */

        // Headers to be returned by the createMerchantBankAccount() mock
        $merchantBankAccountHeaders = file_get_contents(__DIR__.'/Mocks/merchantBankAccountHeaders.json');

        // Create an instance of MockHandler for returning responses for createMerchantBankAccount()
        $merchantBankAccountMock = new MockHandler([
            new Response(201, json_decode($merchantBankAccountHeaders, true)),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $merchantBankAccountHandler = HandlerStack::create($merchantBankAccountMock);

        // Create a new instance of client using the createMerchantBankAccount() handler
        $merchantBankAccountClient = new Client(['handler' => $merchantBankAccountHandler]);

        // Use $merchantBankAccountClient to create an instance of the SettlementTransferService() class
        $this->merchantBankAccountClient = new SettlementTransferService($merchantBankAccountClient, $options);

        /*
        *    createMerchantWallet() setup
        */

        // Headers to be returned by the createMerchantWallet mock
        $merchantWalletHeaders = file_get_contents(__DIR__.'/Mocks/merchantWalletHeaders.json');

        // Create an instance of MockHandler for returning responses for createMerchantWallet
        $merchantWalletMock = new MockHandler([
            new Response(201, json_decode($merchantWalletHeaders, true)),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $merchantWalletHandler = HandlerStack::create($merchantWalletMock);

        // Create a new instance of client using the createMerchantWallet handler
        $merchantWalletClient = new Client(['handler' => $merchantWalletHandler]);

        // Use $merchantWalletClient to create an instance of the SetttlementTransferService() class
        $this->merchantWalletClient = new SettlementTransferService($merchantWalletClient, $options);

        /*
        *    settleFunds() setup
        */

        // Headers to be returned by the settleFunds() mock
        $settleFundsHeaders = file_get_contents(__DIR__.'/Mocks/settleFundsHeaders.json');

        // Create an instance of MockHandler for returning responses for settleFunds()
        $settleFundsMock = new MockHandler([
            new Response(201, json_decode($settleFundsHeaders, true)),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $settleFundsHandler = HandlerStack::create($settleFundsMock);

        // Create a new instance of client using the settleFunds() handler
        $settleFundsClient = new Client(['handler' => $settleFundsHandler]);

        // Use $settleFundsClient to create an instance of the SettlementTransferService() class
        $this->settleFundsClient = new SettlementTransferService($settleFundsClient, $options);

        /*
        *    getStatus() setup
        */

        // json response to be returned
        $statusBody = file_get_contents(__DIR__.'/Mocks/transferStatus.json');

        // Create an instance of MockHandler for returning responses for getStatus()
        $statusMock = new MockHandler([
            new Response(200, [], $statusBody),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $statusHandler = HandlerStack::create($statusMock);

        // Create a new instance of client using the getStatus() handler
        $statusClient = new Client(['handler' => $statusHandler]);

        // Use$statusClient to create an instance of the SettlementTransferService() class
        $this->statusClient = new SettlementTransferService($statusClient, $options);
    }

    /*
    *   Create Settlement account tests
    */

    /*
    *   Create Merchant Bank Account
    */

    public function testCreateMerchantBankAccountRTSSucceeds()
    {
        $response = $this->merchantBankAccountClient->createMerchantBankAccount([
            'accountName' => 'my_account_name',
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'accountNumber' => '1234567890',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
            'settlementMethod' => 'RTS',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testCreateMerchantBankAccountWithNoAccountNameFails()
    {
        $response = $this->merchantBankAccountClient->createMerchantBankAccount([
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'accountNumber' => '1234567890',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
            'settlementMethod' => 'RTS',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accountName', $response['data']);
    }

    public function testCreateMerchantBankAccountWithNoBankBranchRefFails()
    {
        $response = $this->merchantBankAccountClient->createMerchantBankAccount([
            'accountName' => 'my_account_name',
            'accountNumber' => '1234567890',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
            'settlementMethod' => 'RTS',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the bankBranchRef', $response['data']);
    }

    public function testCreateMerchantBankAccountWithNoAccountNumberFails()
    {
        $response = $this->merchantBankAccountClient->createMerchantBankAccount([
            'accountName' => 'my_account_name',
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
            'settlementMethod' => 'RTS',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accountNumber', $response['data']);
    }

    public function testCreateMerchantBankAccountWithNoSettlementMethodFails()
    {
        $response = $this->merchantBankAccountClient->createMerchantBankAccount([
            'accountName' => 'my_account_name',
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the settlementMethod', $response['data']);
    }

    public function testCreateMerchantBankAccountWithNoAccessTokenFails()
    {
        $response = $this->merchantBankAccountClient->createMerchantBankAccount([
            'accountName' => 'my_account_name',
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'accountNumber' => '1234567890',
            'settlementMethod' => 'RTS',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }

      /*
    *   Create Merchant Wallet
    */

    public function testCreateMerchantWalletSucceeds()
    {
        $response = $this->merchantWalletClient->createMerchantWallet([
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'network' => 'Safaricom',
            'phoneNumber' => '+254792345678',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testCreateMerchantWalletWithNoFirstNameFails()
    {
        $response = $this->merchantWalletClient->createMerchantWallet([
            'lastName' => 'Doe',
            'network' => 'Safaricom',
            'phoneNumber' => '+254792345678',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the firstName', $response['data']);
    }

    public function testCreateMerchantWalletWithNoLastNameFails()
    {
       $response = $this->merchantWalletClient->createMerchantWallet([
            'firstName' => 'Jane',
            'network' => 'Safaricom',
            'phoneNumber' => '+254792345678',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the lastName', $response['data']);
    }

    public function testCreateMerchantWalletWithNoNetworkFails()
    {
        $response = $this->merchantWalletClient->createMerchantWallet([
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'phoneNumber' => '+254792345678',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the network', $response['data']);
    }

    public function testCreateMerchantWalletWithNoPhoneNumberFails()
    {
        $response = $this->merchantWalletClient->createMerchantWallet([
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'network' => 'Safaricom',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the phoneNumber', $response['data']);
    }

    /*
    *   Settle Funds tests
    */

    public function testTargettedSettleFundsSucceeds()
    {
        $response = $this->settleFundsClient->settleFunds([
            'amount' => 333,
            'currency' => 'KES',
            'destinationType' => 'merchant_wallet',
            'destinationReference' => 'my_destination_ref',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
            'callbackUrl' => 'http://localhost:8000/test',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testBlindSettleFundsSucceeds()
    {
        $response = $this->settleFundsClient->settleFunds([
            'accessToken' => 'myRand0mAcc3ssT0k3n',
            'callbackUrl' => 'http://localhost:8000/test',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testBlindSettleFundsWithNocallbackUrlFails()
    {
        $response = $this->settleFundsClient->settleFunds([
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the callbackUrl', $response['data']);
    }

    public function testTargettedSettleFundsWithNoAccessTokenFails()
    {
        $response = $this->settleFundsClient->settleFunds([
            'amount' => 333,
            'currency' => 'KES',
            'destinationType' => 'merchant_wallet',
            'destinationReference' => 'my_destination_ref',
            'callbackUrl' => 'http://localhost:8000/test',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }

    public function testTargettedSettleFundsWithNocallbackUrlFails()
    {
        $response = $this->settleFundsClient->settleFunds([
            'amount' => 333,
            'currency' => 'KES',
            'destinationType' => 'merchant_wallet',
            'destinationReference' => 'my_destination_ref',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the callbackUrl', $response['data']);
    }

    /*
    *   Settlement Status tests
    */

    public function testGetStatusSucceeds()
    {
        $response = $this->statusClient->getStatus([
            'location' => 'my_request_id',
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
            'location' => 'my_request_id',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }
}
