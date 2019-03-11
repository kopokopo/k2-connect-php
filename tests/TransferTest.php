<?php

namespace Kopokopo\SDK\Tests;

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Kopokopo\SDK\K2;

class TransferTest extends TestCase
{
    public function setup()
    {
        $this->clientId = 'your_client_id';
        $this->clientSecret = '10af7ad062a21d9c841877f87b7dec3dbe51aeb3';

        $k2 = new K2($this->clientId, $this->clientSecret);
        $this->client = $k2->TransferService();
    }

    /*
    *   Create Settlement account tests
    */

    public function testCreateSettlementAccountSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->createSettlementAccount([
                'accountName' => 'my_account_name',
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testCreateSettlementAccountWithNoAccountNameFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accountName'],
            $this->client->createSettlementAccount([
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testCreateSettlementAccountWithNoBankRefFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the bankRef'],
            $this->client->createSettlementAccount([
                'accountName' => 'my_account_name',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testCreateSettlementAccountWithNoBankBranchRefFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the bankBranchRef'],
            $this->client->createSettlementAccount([
                'accountName' => 'my_account_name',
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testCreateSettlementAccountWithNoAccountNumberFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accountNumber'],
            $this->client->createSettlementAccount([
                'accountName' => 'my_account_name',
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testCreateSettlementAccountWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->client->createSettlementAccount([
                'accountName' => 'my_account_name',
                'bankRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'bankBranchRef' => '9ed38155-7d6f-11e3-83c3-5404a6144203',
                'accountNumber' => '1234567890',
            ])
        );
    }

    /*
    *   Settle Funds tests
    */

    public function testSettleFundsSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->settleFunds([
                'amount' => 333,
                'currency' => 'KES',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testSettleFundsWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->client->settleFunds([
                'amount' => 333,
                'currency' => 'KES',
            ])
        );
    }

    public function testSettleFundsWithNoAmountFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the amount'],
            $this->client->settleFunds([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'currency' => 'KES',
            ])
        );
    }

    public function testSettleFundsWithNoCurrencyFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the currency'],
            $this->client->settleFunds([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
                'amount' => 333,
            ])
        );
    }

    /*
    *   Settlement Status tests
    */

    public function testSettlementStatusSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->settlementStatus([
                'location' => 'my_request_id',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testSettlementStatusWithNoLocationFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the location'],
            $this->client->settlementStatus([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testSettlementStatusWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->client->settlementStatus([
                'location' => 'my_request_id',
            ])
        );
    }
}
