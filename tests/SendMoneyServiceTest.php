<?php

namespace Kopokopo\SDK\Tests;
require "vendor/autoload.php";

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Kopokopo\SDK\Helpers\K2Utils;
use Kopokopo\SDK\SendMoneyService;
use PHPUnit\Framework\TestCase;

class SendMoneyServiceTest extends TestCase
{
    private function getK2Options(): array {
       return [
            "clientId" => "your_client_id",
            "clientSecret" => "your_client_secret",
            "apiKey" => "your_api_key",
            "baseUrl" => "http://localhost:8000"
        ];
    }

    function setup(): void
    {
        $k2Options = $this->getK2Options();

        // Set up send money
        $sendMoneyHeaders = file_get_contents(__DIR__."/Mocks/sendMoneyHeaders.json");
        $sendMoneyMock = new MockHandler([new Response(200, json_decode($sendMoneyHeaders, true)),]);
        $sendMoneyHandler = HandlerStack::create($sendMoneyMock);
        $sendMoneyClient = new Client(["handler" => $sendMoneyHandler]);
        $this->sendMoneyService = new SendMoneyService($sendMoneyClient, $k2Options);
    }

    private function setUpSendMoneyStatus(): void {
        $k2Options = $this->getK2Options();

        $sendMoneyStatusPayload = file_get_contents(__DIR__."/Mocks/sendMoneyStatus.json");
        $sendMoneyMock = new MockHandler([new Response(200, [], $sendMoneyStatusPayload)]);
        $sendMoneyHandler = HandlerStack::create($sendMoneyMock);
        $sendMoneyClient = new Client(["handler" => $sendMoneyHandler]);
        $this->sendMoneyService = new SendMoneyService($sendMoneyClient, $k2Options);
    }

    // Send Money to My Accounts (Blind Settlement)
    public function testSendMoneyToMyAccountsSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "currency" => "KES",
            "metadata" => [
                "notes" => "End of day withdrawal",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToMyAccountsWithoutMetadataSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "currency" => "KES",
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToMyAccountsWithoutCurrencyFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "metadata" => [
                "notes" => "End of day withdrawal",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the currency", $response["data"]);
    }

    public function testSendMoneyToMyAccountsWithoutCallbackUrlFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "currency" => "KES",
            "metadata" => [
                "notes" => "End of day withdrawal",
            ],
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the callbackUrl", $response["data"]);
    }

    public function testSendMoneyToMyAccountsWithoutAccessTokenFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "currency" => "KES",
            "metadata" => [
                "notes" => "End of day withdrawal",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accessToken", $response["data"]);
    }

    // Send Money To Merchant Wallet
    public function testSendMoneyToMerchantWalletSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_wallet",
                    "reference" => "af033d43-75f7-4123-b5c3-252377d96739",
                    "amount" => 310.00,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "October salary",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToMerchantWalletWithoutSourceIdentifierSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_wallet",
                    "reference" => "af033d43-75f7-4123-b5c3-252377d96739",
                    "amount" => 310.00,
                ],
            ],
            "currency" => "KES",
            "metadata" => [
                "notes" => "October salary",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToMerchantWalletWithoutMetadataSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_wallet",
                    "reference" => "af033d43-75f7-4123-b5c3-252377d96739",
                    "amount" => 310.00,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToMerchantWalletWithoutReferenceFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_wallet",
                    "amount" => 310.00,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "October salary",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the reference", $response["data"]);
    }

    public function testSendMoneyToMerchantWalletWithoutCurrencyFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_wallet",
                    "reference" => "af033d43-75f7-4123-b5c3-252377d96739",
                    "amount" => 310.00,
                ],
            ],
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "October salary",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the currency", $response["data"]);
    }

    public function testSendMoneyToMerchantWalletWithoutCallbackUrlFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_wallet",
                    "reference" => "af033d43-75f7-4123-b5c3-252377d96739",
                    "amount" => 310.00,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "October salary",
            ],
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the callbackUrl", $response["data"]);
    }

    public function testSendMoneyToMerchantWalletWithoutAccessTokenFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_wallet",
                    "reference" => "af033d43-75f7-4123-b5c3-252377d96739",
                    "amount" => 310.00,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "October salary",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accessToken", $response["data"]);
    }

    // Send Money To Merchant Bank Account
    public function testSendMoneyToMerchantBankAccountSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_bank_account",
                    "reference" => "ffe8280d-0ab4-4b85-9200-bb7d11ccf11d",
                    "amount" => 420.00,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "October salary",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToMerchantBankAccountWithoutSourceIdentifierSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_bank_account",
                    "reference" => "ffe8280d-0ab4-4b85-9200-bb7d11ccf11d",
                    "amount" => 420.00,
                ],
            ],
            "currency" => "KES",
            "metadata" => [
                "notes" => "October salary",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToMerchantBankAccountWithoutMetadataSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_bank_account",
                    "reference" => "ffe8280d-0ab4-4b85-9200-bb7d11ccf11d",
                    "amount" => 420.00,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToMerchantBankAccountWithoutReferenceFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_bank_account",
                    "amount" => 420.00,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "October salary",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the reference", $response["data"]);
    }

    public function testSendMoneyToMerchantBankAccountWithoutCurrencyFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_bank_account",
                    "reference" => "ffe8280d-0ab4-4b85-9200-bb7d11ccf11d",
                    "amount" => 420.00,
                ],
            ],
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "October salary",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the currency", $response["data"]);
    }

    public function testSendMoneyToMerchantBankAccountWithoutCallbackUrlFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_bank_account",
                    "reference" => "ffe8280d-0ab4-4b85-9200-bb7d11ccf11d",
                    "amount" => 420.00,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "October salary",
            ],
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the callbackUrl", $response["data"]);
    }

    public function testSendMoneyToMerchantBankAccountWithoutAccessTokenFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_bank_account",
                    "reference" => "ffe8280d-0ab4-4b85-9200-bb7d11ccf11d",
                    "amount" => 420.00,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "October salary",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accessToken", $response["data"]);
    }

    // Send Money to External Wallet
    public function testSendMoneyToExternalWalletSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "mobile_wallet",
                    "nickname" => "John Doe",
                    "phoneNumber" => "254900000000",
                    "network" => "Safaricom",
                    "amount" => 100.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalWalletWithoutSourceIdentifierSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "mobile_wallet",
                    "nickname" => "John Doe",
                    "phoneNumber" => "254900000000",
                    "network" => "Safaricom",
                    "amount" => 100.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalWalletWithoutMetadataSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "mobile_wallet",
                    "nickname" => "John Doe",
                    "phoneNumber" => "254900000000",
                    "network" => "Safaricom",
                    "amount" => 100.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalWalletWithoutFavouriteSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "mobile_wallet",
                    "nickname" => "John Doe",
                    "phoneNumber" => "254900000000",
                    "network" => "Safaricom",
                    "amount" => 100.00,
                    "description" => "Contractor payment",
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalWalletWithoutNicknameSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "mobile_wallet",
                    "phoneNumber" => "254900000000",
                    "network" => "Safaricom",
                    "amount" => 100.00,
                    "description" => "Contractor payment",
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalWalletWithoutTypeFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "nickname" => "John Doe",
                    "phoneNumber" => "254900000000",
                    "network" => "Safaricom",
                    "amount" => 100.00,
                    "description" => "Contractor payment",
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the destination type", $response["data"]);
    }

    public function testSendMoneyToExternalWalletWithoutPhoneNumberFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "mobile_wallet",
                    "nickname" => "Jane Doe",
                    "network" => "Safaricom",
                    "amount" => 100.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the phoneNumber", $response["data"]);
    }

    public function testSendMoneyToExternalWalletWithInvalidPhoneNumberFormatFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "mobile_wallet",
                    "nickname" => "John Doe",
                    "phoneNumber" => "12300000000",
                    "network" => "Safaricom",
                    "amount" => 100.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("Invalid phone number format", $response["data"]);
    }

    public function testSendMoneyToExternalWalletWithoutNetworkFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "mobile_wallet",
                    "nickname" => "Jane Doe",
                    "phoneNumber" => "254911111111",
                    "amount" => 100.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the network", $response["data"]);
    }

    public function testSendMoneyToExternalWalletWithoutDescriptionFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "mobile_wallet",
                    "nickname" => "Jane Doe",
                    "phoneNumber" => "254911111111",
                    "network" => "Safaricom",
                    "amount" => 100.00,
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the description", $response["data"]);
    }

    public function testSendMoneyToExternalWalletWithoutCurrencyFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "mobile_wallet",
                    "nickname" => "John Doe",
                    "phoneNumber" => "254900000000",
                    "network" => "Safaricom",
                    "amount" => 100.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the currency", $response["data"]);
    }

    public function testSendMoneyToExternalWalletWithoutCallbackUrlFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "mobile_wallet",
                    "nickname" => "John Doe",
                    "phoneNumber" => "254900000000",
                    "network" => "Safaricom",
                    "amount" => 100.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the callbackUrl", $response["data"]);
    }

    public function testSendMoneyToExternalWalletWithoutAccessTokenFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "mobile_wallet",
                    "nickname" => "John Doe",
                    "phoneNumber" => "254900000000",
                    "network" => "Safaricom",
                    "amount" => 100.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accessToken", $response["data"]);
    }

    // Send Money to External Bank Account
    public function testSendMoneyToExternalBankAccountSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "bank_account",
                    "bankBranchRef" => "bc59f5c9-0eef-4690-8f83-005840cc474a",
                    "accountName" => "Test Bank Account",
                    "accountNumber" => "0123456789014",
                    "nickname" => "Test Bank Account",
                    "amount" => 4200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalBankAccountWithoutSourceIdentifierSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "bank_account",
                    "bankBranchRef" => "bc59f5c9-0eef-4690-8f83-005840cc474a",
                    "accountName" => "Test Bank Account",
                    "accountNumber" => "0123456789014",
                    "nickname" => "Test Bank Account",
                    "amount" => 4200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalBankAccountWithoutMetadataSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "bank_account",
                    "bankBranchRef" => "bc59f5c9-0eef-4690-8f83-005840cc474a",
                    "accountName" => "Test Bank Account",
                    "accountNumber" => "0123456789014",
                    "nickname" => "Test Bank Account",
                    "amount" => 4200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalBankAccountWithoutFavouriteSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "bank_account",
                    "bankBranchRef" => "bc59f5c9-0eef-4690-8f83-005840cc474a",
                    "accountName" => "Test Bank Account",
                    "accountNumber" => "0123456789014",
                    "nickname" => "Test Bank Account",
                    "amount" => 4200.00,
                    "description" => "Contractor payment",
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalBankAccountWithoutNicknameSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "bank_account",
                    "bankBranchRef" => "bc59f5c9-0eef-4690-8f83-005840cc474a",
                    "accountName" => "Test Bank Account",
                    "accountNumber" => "0123456789014",
                    "amount" => 4200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalBankAccountWithoutTypeFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "bankBranchRef" => "bc59f5c9-0eef-4690-8f83-005840cc474a",
                    "accountName" => "Test Bank Account",
                    "accountNumber" => "0123456789014",
                    "nickname" => "Test Bank Account",
                    "amount" => 4200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the destination type", $response["data"]);
    }

    public function testSendMoneyToExternalBankAccountWithoutBankBranchReferenceFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "bank_account",
                    "accountName" => "Test Bank Account",
                    "accountNumber" => "0123456789014",
                    "nickname" => "Test Bank Account",
                    "amount" => 4200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the bankBranchRef", $response["data"]);
    }

    public function testSendMoneyToExternalBankAccountWithoutAccountNameFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "bank_account",
                    "bankBranchRef" => "bc59f5c9-0eef-4690-8f83-005840cc474a",
                    "accountNumber" => "0123456789014",
                    "nickname" => "Test Bank Account",
                    "amount" => 4200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accountName", $response["data"]);
    }

    public function testSendMoneyToExternalBankAccountWithoutAccountNumberFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "bank_account",
                    "bankBranchRef" => "bc59f5c9-0eef-4690-8f83-005840cc474a",
                    "accountName" => "Test Bank Account",
                    "nickname" => "Test Bank Account",
                    "amount" => 4200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accountNumber", $response["data"]);
    }

    public function testSendMoneyToExternalBankAccountWithoutDescriptionFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "bank_account",
                    "bankBranchRef" => "bc59f5c9-0eef-4690-8f83-005840cc474a",
                    "accountName" => "Test Bank Account",
                    "accountNumber" => "0123456789014",
                    "nickname" => "Test Bank Account",
                    "amount" => 4200.00,
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the description", $response["data"]);
    }
    public function testSendMoneyToExternalBankAccountWithoutCurrencyFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "bank_account",
                    "bankBranchRef" => "bc59f5c9-0eef-4690-8f83-005840cc474a",
                    "accountName" => "Test Bank Account",
                    "accountNumber" => "0123456789014",
                    "nickname" => "Test Bank Account",
                    "amount" => 4200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the currency", $response["data"]);
    }

    public function testSendMoneyToExternalBankAccountWithoutCallbackUrlFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "bank_account",
                    "bankBranchRef" => "bc59f5c9-0eef-4690-8f83-005840cc474a",
                    "accountName" => "Test Bank Account",
                    "accountNumber" => "0123456789014",
                    "nickname" => "Test Bank Account",
                    "amount" => 4200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the callbackUrl", $response["data"]);
    }

    public function testSendMoneyToExternalBankAccountWithoutAccessTokenFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "bank_account",
                    "bankBranchRef" => "bc59f5c9-0eef-4690-8f83-005840cc474a",
                    "accountName" => "Test Bank Account",
                    "accountNumber" => "0123456789014",
                    "nickname" => "Test Bank Account",
                    "amount" => 4200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accessToken", $response["data"]);
    }

    // Send Money to External Till
    public function testSendMoneyToExternalTillSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "till",
                    "tillNumber" => "1234567",
                    "nickname" => "External Till",
                    "amount" => 1200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalTillWithoutSourceIdentifierSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "till",
                    "tillNumber" => "1234567",
                    "nickname" => "External Till",
                    "amount" => 1200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalTillWithoutMetadataSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "till",
                    "tillNumber" => "1234567",
                    "nickname" => "External Till",
                    "amount" => 1200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalTillWithoutFavouriteSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "till",
                    "tillNumber" => "1234567",
                    "nickname" => "External Till",
                    "amount" => 1200.00,
                    "description" => "Contractor payment",
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalTillWithoutNicknameSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "till",
                    "tillNumber" => "1234567",
                    "amount" => 1200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalTillWithoutTypeFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "tillNumber" => "1234567",
                    "nickname" => "External Till",
                    "amount" => 1200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the destination type", $response["data"]);
    }

    public function testSendMoneyToExternalTillWithoutTillNumberFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "till",
                    "nickname" => "External Till",
                    "amount" => 1200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the tillNumber", $response["data"]);
    }

    public function testSendMoneyToExternalTillWithoutDescriptionFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "till",
                    "tillNumber" => "1234567",
                    "nickname" => "External Till",
                    "amount" => 1200.00,
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the description", $response["data"]);
    }

    public function testSendMoneyToExternalTillWithoutCurrencyFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "till",
                    "tillNumber" => "1234567",
                    "nickname" => "External Till",
                    "amount" => 1200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the currency", $response["data"]);
    }

    public function testSendMoneyToExternalTillWithoutCallbackUrlFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "till",
                    "tillNumber" => "1234567",
                    "nickname" => "External Till",
                    "amount" => 1200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the callbackUrl", $response["data"]);
    }

    public function testSendMoneyToExternalTillWithoutAccessTokenFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "till",
                    "tillNumber" => "1234567",
                    "nickname" => "External Till",
                    "amount" => 1200.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accessToken", $response["data"]);
    }

    // Send Money to External Paybill
    public function testSendMoneyToExternalPaybillSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "paybill",
                    "paybillNumber" => "840106",
                    "paybillAccountNumber" => "A098457",
                    "nickname" => "Test Paybill",
                    "amount" => 700.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalPaybillWithoutSourceIdentifierSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "paybill",
                    "paybillNumber" => "840106",
                    "paybillAccountNumber" => "A098457",
                    "nickname" => "Test Paybill",
                    "amount" => 700.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalPaybillWithoutMetadataSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "paybill",
                    "paybillNumber" => "840106",
                    "paybillAccountNumber" => "A098457",
                    "nickname" => "Test Paybill",
                    "amount" => 700.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalPaybillWithoutFavouriteSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "paybill",
                    "paybillNumber" => "840106",
                    "paybillAccountNumber" => "A098457",
                    "nickname" => "Test Paybill",
                    "amount" => 700.00,
                    "description" => "Contractor payment",
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalPaybillWithoutNicknameSucceeds(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "paybill",
                    "paybillNumber" => "840106",
                    "paybillAccountNumber" => "A098457",
                    "amount" => 700.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a", $response["location"]);
    }

    public function testSendMoneyToExternalPaybillWithoutTypeFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "paybillNumber" => "840106",
                    "paybillAccountNumber" => "A098457",
                    "nickname" => "Test Paybill",
                    "amount" => 700.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the destination type", $response["data"]);
    }

    public function testSendMoneyToExternalPaybillWithoutPaybillNumberFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "paybill",
                    "paybillAccountNumber" => "A098457",
                    "nickname" => "Test Paybill",
                    "amount" => 700.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the paybillNumber", $response["data"]);
    }

    public function testSendMoneyToExternalPaybillWithoutAccountNumberFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "paybill",
                    "paybillNumber" => "840106",
                    "nickname" => "Test Paybill",
                    "amount" => 700.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the paybillAccountNumber", $response["data"]);
    }

    public function testSendMoneyToExternalPaybillWithoutDescriptionFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "paybill",
                    "paybillNumber" => "840106",
                    "paybillAccountNumber" => "A098457",
                    "nickname" => "Test Paybill",
                    "amount" => 700.00,
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the description", $response["data"]);
    }

    public function testSendMoneyToExternalPaybillWithoutCurrencyFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "paybill",
                    "paybillNumber" => "840106",
                    "paybillAccountNumber" => "A098457",
                    "nickname" => "Test Paybill",
                    "amount" => 700.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the currency", $response["data"]);
    }

    public function testSendMoneyToExternalPaybillWithoutCallbackUrlFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "paybill",
                    "paybillNumber" => "840106",
                    "paybillAccountNumber" => "A098457",
                    "nickname" => "Test Paybill",
                    "amount" => 700.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the callbackUrl", $response["data"]);
    }

    public function testSendMoneyToExternalPaybillWithoutAccessTokenFails(): void {
        $response = $this->sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "paybill",
                    "paybillNumber" => "840106",
                    "paybillAccountNumber" => "A098457",
                    "nickname" => "Test Paybill",
                    "amount" => 700.00,
                    "description" => "Contractor payment",
                    "favourite" => true,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "1234",
            "metadata" => [
                "notes" => "For your services",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accessToken", $response["data"]);
    }

    // Bad request exception handling
    public function testSendMoneyGracefullyHandlesRequestExceptions(): void {
        $k2Options = $this->getK2Options();
        $sendMoneyError = file_get_contents(__DIR__."/Mocks/sendMoneyError.json");
        $sendMoneyMock = new MockHandler([new Response(400, [], $sendMoneyError),]);
        $sendMoneyHandler = HandlerStack::create($sendMoneyMock);
        $sendMoneyClient = new Client(["handler" => $sendMoneyHandler]);
        $sendMoneyService = new SendMoneyService($sendMoneyClient, $k2Options);
        $response = $sendMoneyService->sendMoney([
            "destinations" => [
                [
                    "type" => "merchant_wallet",
                    "reference" => "af033d43-75f7-4123-b5c3-252377d96739",
                    "amount" => 310.00,
                ],
            ],
            "currency" => "KES",
            "sourceIdentifier" => "0000",
            "metadata" => [
                "notes" => "October salary",
            ],
            "callbackUrl" => "http://localhost:8000/send_money_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("400", $response["data"]["errorCode"]);
        $this->assertEquals("Source identifier is invalid", $response["data"]["errorMessage"]);
    }

    // Query send money request status
    public function testGetSendMoneyStatusSucceeds()
    {
        $this->setUpSendMoneyStatus();
        $result = json_decode(file_get_contents(__DIR__."/Mocks/sendMoneyStatus.json"), true)["data"];
        $response = $this->sendMoneyService->getStatus([
            "location" => "http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $formattedResult = [
            "id" => $result["id"],
            "type" => $result["type"],
            "createdAt" => $result["attributes"]["created_at"],
            "status" => $result["attributes"]["status"],
            "sourceIdentifier" => $result["attributes"]["source_identifier"],
            "destinations" => K2Utils::deepCamelizeKeys($result["attributes"]["destinations"]),
            "currency" => $result["attributes"]["currency"],
            "transferBatches" => K2Utils::deepCamelizeKeys($result["attributes"]["transfer_batches"]),
            "errors" => $result["attributes"]["errors"],
            "metadata" => $result["attributes"]["metadata"],
            "linkSelf" => $result["attributes"]["_links"]["self"],
            "callbackUrl" => $result["attributes"]["_links"]["callback_url"],
        ];

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals($formattedResult, $response["data"]);
    }

    public function testGetSendMoneyStatusWithNoLocationFails()
    {
        $this->setUpSendMoneyStatus();
        $response = $this->sendMoneyService->getStatus([
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the location", $response["data"]);
    }

    public function testGetSendMoneyStatusWithNoAccessTokenFails()
    {
        $this->setUpSendMoneyStatus();
        $response = $this->sendMoneyService->getStatus([
            "location" => "http://localhost:3000/api/v2/send_money/716481bc-635c-4d35-92c9-65bb0797196a",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accessToken", $response["data"]);
    }
}