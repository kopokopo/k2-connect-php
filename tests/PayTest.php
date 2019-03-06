<?php

namespace Kopokopo\SDK\Tests;

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Kopokopo\SDK\K2;

class PayTest extends TestCase
{
    public function setup()
    {
        $this->clientId = 'your_client_id';
        $this->clientSecret = '10af7ad062a21d9c841877f87b7dec3dbe51aeb3';

        $k2 = new K2($this->clientId, $this->clientSecret);
        $this->client = $k2->PayService();
    }

    /*
    *   Add Pay Recipient (Mobile) tests
    */

    public function testAddPayRecipientMobile()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->addPayRecipient([
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

    public function testAddPayRecipientMobileWithNoFirstName()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the firstName'],
            $this->client->addPayRecipient([
                'type' => 'mobile_wallet',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'network' => 'safaricom',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientMobileWithNoLastName()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the lastName'],
            $this->client->addPayRecipient([
                'type' => 'mobile_wallet',
                'firstName' => 'Jane',
                'phone' => '+254712345678',
                'network' => 'safaricom',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientMobileWithNoPhone()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the phone'],
            $this->client->addPayRecipient([
                'type' => 'mobile_wallet',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'network' => 'safaricom',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientMobileWithInvalidPhoneFormat()
    {
        $this->assertArraySubset(
            ['data' => 'Invalid phone format'],
            $this->client->addPayRecipient([
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

    public function testAddPayRecipientMobileWithNoNetwork()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the network'],
            $this->client->addPayRecipient([
                'type' => 'mobile_wallet',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'email' => 'example@example.com',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientMobileWithNoEmail()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->addPayRecipient([
                'type' => 'mobile_wallet',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phone' => '+254712345678',
                'network' => 'safaricom',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testAddPayRecipientMobileWithNoAccessToken()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->client->addPayRecipient([
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

    public function testAddPayRecipientAccount()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->addPayRecipient([
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

    public function testAddPayRecipientAccountWithNoAccountName()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accountName'],
            $this->client->addPayRecipient([
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

    public function testAddPayRecipientAccountWithNoBankRef()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the bankRef'],
            $this->client->addPayRecipient([
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

    public function testAddPayRecipientAccountWithNoBankBranchRef()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the bankBranchRef'],
            $this->client->addPayRecipient([
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

    public function testAddPayRecipientAccountWithNoAccountNumber()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accountNumber'],
            $this->client->addPayRecipient([
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

    public function testAddPayRecipientAccountWithNoPhone()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->addPayRecipient([
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

    public function testAddPayRecipientAccountWithInvalidPhoneFormat()
    {
        $this->assertArraySubset(
            ['data' => 'Invalid phone format'],
            $this->client->addPayRecipient([
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

    public function testAddPayRecipientAccountWithNoEmail()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->addPayRecipient([
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

    public function testAddPayRecipientAccountWithNoAccessToken()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->client->addPayRecipient([
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

    public function testAddPayRecipientWithNoType()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the type'],
            $this->client->addPayRecipient([
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

    public function testSendPay()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->sendPay([
                'destination' => 'my_destination_alias',
                'amount' => 3444,
                'currency' => 'KES',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'callbackUrl' => 'http://localhost:8000/webhook',
            ])
        );
    }

    public function testSendPayWithNoDestination()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the destination'],
            $this->client->sendPay([
                'amount' => 3444,
                'currency' => 'KES',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'callbackUrl' => 'http://localhost:8000/webhook',
            ])
        );
    }

    public function testSendPayWithNoAmount()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the destination'],
            $this->client->sendPay([
                'currency' => 'KES',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'callbackUrl' => 'http://localhost:8000/webhook',
            ])
        );
    }

    public function testSendPayWithNoCurrency()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the currency'],
            $this->client->sendPay([
                'destination' => 'my_destination_alias',
                'amount' => 3444,
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'callbackUrl' => 'http://localhost:8000/webhook',
            ])
        );
    }

    public function testSendPayWithNoCallbackUrl()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the callbackUrl'],
            $this->client->sendPay([
                'destination' => 'my_destination_alias',
                'amount' => 3444,
                'currency' => 'KES',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testSendPayWithNoAccessToken()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->client->sendPay([
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
            $this->client->payStatus([
                'location' => 'my_request_id',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPayStatusWithNoLocation()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the location'],
            $this->client->payStatus([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testPayStatusWithNoAccessToken()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->client->payStatus([
                'location' => 'my_request_id',
            ])
        );
    }
}
