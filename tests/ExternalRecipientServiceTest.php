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
use Kopokopo\SDK\ExternalRecipientService;

class ExternalRecipientServiceTest extends TestCase
{
    private $externalRecipientClient;

    public function setup(): void
    {
        $options = [
            'clientId' => 'your_client_id',
            'clientSecret' => 'your_client_secret',
            'apiKey' => 'your_api_key',
            'baseUrl' => 'https://9284bede-d6e9f8d86aff.mock.pstmn.io'
        ];

        // Headers to be returned by the addExternalRecipient() mock
        $externalRecipientHeaders = file_get_contents(__DIR__.'/Mocks/externalRecipientHeaders.json');

        // Create an instance of MockHandler for returning responses for addExternalRecipient()
        $externalRecipientMock = new MockHandler([
            new Response(200, json_decode($externalRecipientHeaders, true)),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $externalRecipientHandler = HandlerStack::create($externalRecipientMock);

        // Create a new instance of client using the createExternalRecipient() handler
        $externalRecipientClient = new Client(['handler' => $externalRecipientHandler]);

        // Use $externalRecipientClient to create an instance of the ExternalRecipientService() class
        $this->externalRecipientClient = new ExternalRecipientService($externalRecipientClient, $options);
    }

    /*
    *   Add External Mobile Wallet Recipient tests
    */

    public function testAddExternalMobileWalletRecipientSucceeds()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
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

    public function testAddExternalMobileWalletRecipientWithNoFirstNameFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
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

    public function testAddExternalMobileWalletRecipientWithNoLastNameFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
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

    public function testAddExternalMobileWalletRecipientWithNoPhoneFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
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

    public function testAddExternalMobileWalletRecipientWithInvalidPhoneFormatFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
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

    public function testAddExternalMobileWalletRecipientWithNoNetworkFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
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

    public function testAddExternalMobileWalletRecipientWithNoEmailSucceeds()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
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

    public function testAddExternalMobileWalletRecipientWithNoAccessTokenFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
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
    *   Add External Bank Account Recipient tests
    */

    public function testAddExternalBankAccountRecipientSucceeds()
    {
       $response = $this->externalRecipientClient->addExternalRecipient([
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

    public function testAddExternalBankAccountRecipientWithNoAccountNameFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
            'type' => 'bank_account',
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'accountNumber' => '1234567890',
            'settlementMethod' => 'EFT',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accountName', $response['data']);
    }

    public function testAddExternalBankAccountRecipientWithNoBankBranchRefFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
            'type' => 'bank_account',
            'accountName' => 'Doe',
            'accountNumber' => '1234567890',
            'settlementMethod' => 'EFT',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the bankBranchRef', $response['data']);
    }

    public function testAddExternalBankAccountRecipientWithNoAccountNumberFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
            'type' => 'bank_account',
            'accountName' => 'Doe',
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'settlementMethod' => 'EFT',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accountNumber', $response['data']);
    }

    public function testAddExternalBankAccountRecipientWithNoSettlementMethodFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
            'type' => 'bank_account',
            'accountName' => 'Doe',
            'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
            'accountNumber' => '1234567890',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the settlementMethod', $response['data']);
    }

    public function testAddExternalBankAccountRecipientWithNoAccessTokenFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
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
    *   Add External Till Recipient tests
    */

    public function testAddExternalTillRecipientSucceeds()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
            'type' => 'till',
            'tillName' => 'Doe',
            'tillNumber' => '123456',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testAddExternalTillRecipientWithNoTillNameFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
            'type' => 'till',
            'tillNumber' => '123456',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the tillName', $response['data']);
    }

    public function testAddExternalTillRecipientWithNoTillNumberFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
            'type' => 'till',
            'tillName' => 'Doe',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the tillNumber', $response['data']);
    }

    public function testAddExternalTillRecipientWithNoAccessTokenFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
            'type' => 'till',
            'tillName' => 'Doe',
            'tillNumber' => '123456',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }

    /*
    *   Add External Paybill Recipient tests
    */

    public function testAddExternalPaybillRecipientSucceeds()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
            'type' => 'paybill',
            'paybillName' => 'Doe',
            'paybillNumber' => '123456',
            'paybillAccountNumber' => '67890',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testAddExternalPaybillRecipientWithNoPaybillNameFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
            'type' => 'paybill',
            'paybillNumber' => '123456',
            'paybillAccountNumber' => '67890',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the paybillName', $response['data']);
    }

    public function testAddExternalPaybillRecipientWithNoPaybillNumberFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
            'type' => 'paybill',
            'paybillName' => 'Doe',
            'paybillAccountNumber' => '67890',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the paybillNumber', $response['data']);
    }

    public function testAddExternalPaybillRecipientWithNoPaybillAccountNumberFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
            'type' => 'paybill',
            'paybillName' => 'Doe',
            'paybillNumber' => '123456',
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the paybillAccountNumber', $response['data']);
    }

    public function testAddExternalPaybillRecipientWithNoAccessTokenFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
            'type' => 'paybill',
            'paybillName' => 'Doe',
            'paybillNumber' => '123456',
            'paybillAccountNumber' => '67890',
        ]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }

    /*
    *   Add External Recipient tests
    */

    public function testAddExternalRecipientWithNoTypeFails()
    {
        $response = $this->externalRecipientClient->addExternalRecipient([
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
}
