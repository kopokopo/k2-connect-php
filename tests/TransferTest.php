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

    public function testCreateSettlementAccount()
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

    public function testCreateSettlementAccountWithNoAccountName()
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

    public function testCreateSettlementAccountWithNoBankRef()
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

    public function testCreateSettlementAccountWithNoBankBranchRef()
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

    public function testCreateSettlementAccountWithNoAccountNumber()
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

    public function testCreateSettlementAccountWithNoAccessToken()
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

    public function testSettleFunds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->settleFunds([
                'amount' => 333,
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testSettleFundsWithNoAccessToken()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->client->settleFunds([
                'amount' => 333,
            ])
        );
    }

    public function testSettleFundsWithNoAmount()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the amount'],
            $this->client->settleFunds([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    /*
    *   Settlement Status tests
    */

    public function testSettlementStatus()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->client->settlementStatus([
                'location' => 'my_request_id',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testSettlementStatusWithNoLocation()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the location'],
            $this->client->settlementStatus([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testSettlementStatusWithNoAccessToken()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->client->settlementStatus([
                'location' => 'my_request_id',
            ])
        );
    }
}
