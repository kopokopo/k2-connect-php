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
    public function setup()
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
        $statusBody = file_get_contents(__DIR__.'/Mocks/transfer-status.json');

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
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->merchantBankAccountClient->createMerchantBankAccount([
                'accountName' => 'my_account_name',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'settlementMethod' => 'RTS',
            ])
        );
    }

    public function testCreateMerchantBankAccountWithNoAccountNameFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accountName'],
            $this->merchantBankAccountClient->createMerchantBankAccount([
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'settlementMethod' => 'RTS',
            ])
        );
    }

    public function testCreateMerchantBankAccountWithNoBankBranchRefFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the bankBranchRef'],
            $this->merchantBankAccountClient->createMerchantBankAccount([
                'accountName' => 'my_account_name',
                'accountNumber' => '1234567890',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'settlementMethod' => 'RTS',
            ])
        );
    }

    public function testCreateMerchantBankAccountWithNoAccountNumberFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accountNumber'],
            $this->merchantBankAccountClient->createMerchantBankAccount([
                'accountName' => 'my_account_name',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'settlementMethod' => 'RTS',
            ])
        );
    }

    public function testCreateMerchantBankAccountWithNoSettlementMethodFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the settlementMethod'],
            $this->merchantBankAccountClient->createMerchantBankAccount([
                'accountName' => 'my_account_name',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testCreateMerchantBankAccountWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->merchantBankAccountClient->createMerchantBankAccount([
                'accountName' => 'my_account_name',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'settlementMethod' => 'RTS',
            ])
        );
    }

      /*
    *   Create Merchant Wallet
    */

    public function testCreateMerchantWalletSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->merchantWalletClient->createMerchantWallet([
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'network' => 'Safaricom',
                'phoneNumber' => '+254792345678',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testCreateMerchantWalletWithNoFirstNameFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the firstName'],
            $this->merchantWalletClient->createMerchantWallet([
                'lastName' => 'Doe',
                'network' => 'Safaricom',
                'phoneNumber' => '+254792345678',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testCreateMerchantWalletWithNoLastNameFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the lastName'],
            $this->merchantWalletClient->createMerchantWallet([
                'firstName' => 'Jane',
                'network' => 'Safaricom',
                'phoneNumber' => '+254792345678',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testCreateMerchantWalletWithNoNetworkFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the network'],
            $this->merchantWalletClient->createMerchantWallet([
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phoneNumber' => '+254792345678',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testCreateMerchantWalletWithNoPhoneNumberFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the phoneNumber'],
            $this->merchantWalletClient->createMerchantWallet([
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'network' => 'Safaricom',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    /*
    *   Settle Funds tests
    */

    public function testTargettedSettleFundsSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->settleFundsClient->settleFunds([
                'amount' => 333,
                'currency' => 'KES',
                'destinationType' => 'merchant_wallet',
                'destinationReference' => 'my_destination_ref',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'callbackUrl' => 'http://localhost:8000/test',
            ])
        );
    }

    public function testBlindSettleFundsSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->settleFundsClient->settleFunds([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'callbackUrl' => 'http://localhost:8000/test',
            ])
        );
    }

    public function testBlindSettleFundsWithNocallbackUrlFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the callbackUrl'],
            $this->settleFundsClient->settleFunds([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testTargettedSettleFundsWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->settleFundsClient->settleFunds([
                'amount' => 333,
                'currency' => 'KES',
                'destinationType' => 'merchant_wallet',
                'destinationReference' => 'my_destination_ref',
                'callbackUrl' => 'http://localhost:8000/test',
            ])
        );
    }

    public function testTargettedSettleFundsWithNocallbackUrlFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the callbackUrl'],
            $this->settleFundsClient->settleFunds([
                'amount' => 333,
                'currency' => 'KES',
                'destinationType' => 'merchant_wallet',
                'destinationReference' => 'my_destination_ref',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    /*
    *   Settlement Status tests
    */

    public function testGetStatusSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->statusClient->getStatus([
                'location' => 'my_request_id',
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
                'location' => 'my_request_id',
            ])
        );
    }
}
