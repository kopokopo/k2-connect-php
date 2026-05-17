<?php

namespace Kopokopo\SDK\Tests;
require "vendor/autoload.php";

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Kopokopo\SDK\Helpers\K2Utils;
use Kopokopo\SDK\PaymentLinkService;
use PHPUnit\Framework\TestCase;

class PaymentLinkServiceTest extends TestCase
{
    private PaymentLinkService $paymentLinkService;

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

        $paymentLinkRequestHeaders = file_get_contents(__DIR__."/Mocks/paymentLinks/paymentLinkHeaders.json");
        $paymentLinkMock = new MockHandler([new Response(200, json_decode($paymentLinkRequestHeaders, true)),]);
        $paymentLinkHandler = HandlerStack::create($paymentLinkMock);
        $paymentLinkClient = new Client(["handler" => $paymentLinkHandler]);
        $this->paymentLinkService = new PaymentLinkService($paymentLinkClient, $k2Options);
    }

    private function setUpGetPaymentLinkStatus(): void {
        $k2Options = $this->getK2Options();

        $paymentLinkStatusPayload = file_get_contents(__DIR__."/Mocks/paymentLinks/paymentLinkStatus.json");
        $paymentLinkMock = new MockHandler([new Response(200, [], $paymentLinkStatusPayload)]);
        $paymentLinkHandler = HandlerStack::create($paymentLinkMock);
        $paymentLinkClient = new Client(["handler" => $paymentLinkHandler]);
        $this->paymentLinkService = new PaymentLinkService($paymentLinkClient, $k2Options);
    }

    private function setUpCancelPaymentLink(): void {
        $k2Options = $this->getK2Options();

        $paymentLinkCancellationResponse = file_get_contents(__DIR__."/Mocks/paymentLinks/paymentLinkCancellation.json");
        $paymentLinkCancellationMock = new MockHandler([new Response(200, [], $paymentLinkCancellationResponse)]);
        $paymentLinkCancellationHandler = HandlerStack::create($paymentLinkCancellationMock);
        $paymentLinkClient = new Client(["handler" => $paymentLinkCancellationHandler]);
        $this->paymentLinkService = new PaymentLinkService($paymentLinkClient, $k2Options);
    }

    private function setUpPaymentLinkRequestError(): void {
        $k2Options = $this->getK2Options();
        $paymentLinkError = file_get_contents(__DIR__."/Mocks/paymentLinks/paymentLinkError.json");
        $paymentLinkMock = new MockHandler([new Response(400, [], $paymentLinkError),]);
        $paymentLinkHandler = HandlerStack::create($paymentLinkMock);
        $paymentLinkClient = new Client(["handler" => $paymentLinkHandler]);
        $this->paymentLinkService = new PaymentLinkService($paymentLinkClient, $k2Options);
    }

    // Create payment link
    public function testCreatePaymentLinkSucceeds()
    {
        $response = $this->paymentLinkService->createPaymentLink([
            "tillNumber" => "4321",
            "currency" => "KES",
            "amount" => 2000.00,
            "paymentReference" => "INV29390943",
            "note" => "Note to customer",
            "metadata" => [
                "promotionDetails" => "End of Year Sale",
                "deliveryAddress" => "1234, ABC Street",
            ],
            "callbackUrl" => "http://localhost:8000/payment_link_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("https://sandbox.kopokopo.com/api/v2/payment_links/d5963294-22bf-46f6-a13c-b6a9951b4312", $response["location"]);
    }

    public function testCreatePaymentLinkWithoutPaymentReferenceSucceeds()
    {
        $response = $this->paymentLinkService->createPaymentLink([
            "tillNumber" => "4321",
            "currency" => "KES",
            "amount" => 2000.00,
            "note" => "Note to customer",
            "metadata" => [
                "promotionDetails" => "End of Year Sale",
                "deliveryAddress" => "1234, ABC Street",
            ],
            "callbackUrl" => "http://localhost:8000/payment_link_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("https://sandbox.kopokopo.com/api/v2/payment_links/d5963294-22bf-46f6-a13c-b6a9951b4312", $response["location"]);
    }

    public function testCreatePaymentLinkWithoutNoteSucceeds()
    {
        $response = $this->paymentLinkService->createPaymentLink([
            "tillNumber" => "4321",
            "currency" => "KES",
            "amount" => 2000.00,
            "paymentReference" => "INV29390943",
            "metadata" => [
                "promotionDetails" => "End of Year Sale",
                "deliveryAddress" => "1234, ABC Street",
            ],
            "callbackUrl" => "http://localhost:8000/payment_link_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("https://sandbox.kopokopo.com/api/v2/payment_links/d5963294-22bf-46f6-a13c-b6a9951b4312", $response["location"]);
    }

    public function testCreatePaymentLinkWithoutMetadataSucceeds()
    {
        $response = $this->paymentLinkService->createPaymentLink([
            "tillNumber" => "4321",
            "currency" => "KES",
            "amount" => 2000.00,
            "paymentReference" => "INV29390943",
            "note" => "Note to customer",
            "callbackUrl" => "http://localhost:8000/payment_link_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("https://sandbox.kopokopo.com/api/v2/payment_links/d5963294-22bf-46f6-a13c-b6a9951b4312", $response["location"]);
    }

    public function testCreatePaymentLinkWithoutTillNumberFails()
    {
        $response = $this->paymentLinkService->createPaymentLink([
            "currency" => "KES",
            "amount" => 2000.00,
            "paymentReference" => "INV29390943",
            "note" => "Note to customer",
            "metadata" => [
                "promotionDetails" => "End of Year Sale",
                "deliveryAddress" => "1234, ABC Street",
            ],
            "callbackUrl" => "http://localhost:8000/payment_link_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the tillNumber", $response["data"]);
    }

    public function testCreatePaymentLinkWithoutCurrencyFails()
    {
        $response = $this->paymentLinkService->createPaymentLink([
            "tillNumber" => "4321",
            "amount" => 2000.00,
            "paymentReference" => "INV29390943",
            "note" => "Note to customer",
            "metadata" => [
                "promotionDetails" => "End of Year Sale",
                "deliveryAddress" => "1234, ABC Street",
            ],
            "callbackUrl" => "http://localhost:8000/payment_link_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the currency", $response["data"]);
    }

    public function testCreatePaymentLinkWithoutAmountFails()
    {
        $response = $this->paymentLinkService->createPaymentLink([
            "tillNumber" => "4321",
            "currency" => "KES",
            "paymentReference" => "INV29390943",
            "note" => "Note to customer",
            "metadata" => [
                "promotionDetails" => "End of Year Sale",
                "deliveryAddress" => "1234, ABC Street",
            ],
            "callbackUrl" => "http://localhost:8000/payment_link_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the amount", $response["data"]);
    }

    public function testCreatePaymentLinkWithoutCallbackUrlFails()
    {
        $response = $this->paymentLinkService->createPaymentLink([
            "tillNumber" => "4321",
            "currency" => "KES",
            "amount" => 2000.00,
            "paymentReference" => "INV29390943",
            "note" => "Note to customer",
            "metadata" => [
                "promotionDetails" => "End of Year Sale",
                "deliveryAddress" => "1234, ABC Street",
            ],
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the callbackUrl", $response["data"]);
    }

    public function testCreatePaymentLinkWithoutAccessTokenFails()
    {
        $response = $this->paymentLinkService->createPaymentLink([
            "tillNumber" => "4321",
            "currency" => "KES",
            "amount" => 2000.00,
            "paymentReference" => "INV29390943",
            "note" => "Note to customer",
            "metadata" => [
                "promotionDetails" => "End of Year Sale",
                "deliveryAddress" => "1234, ABC Street",
            ],
            "callbackUrl" => "http://localhost:8000/payment_link_result",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accessToken", $response["data"]);
    }

    public function testCreatePaymentLinkHandlesRequestExceptionsGracefully()
    {
        $this->setUpPaymentLinkRequestError();
        $response = $this->paymentLinkService->createPaymentLink([
            "tillNumber" => "0000",
            "currency" => "KES",
            "amount" => 2000.00,
            "paymentReference" => "INV29390943",
            "note" => "Note to customer",
            "metadata" => [
                "promotionDetails" => "End of Year Sale",
                "deliveryAddress" => "1234, ABC Street",
            ],
            "callbackUrl" => "http://localhost:8000/payment_link_result",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("400", $response["data"]["errorCode"]);
        $this->assertEquals("Till number is invalid", $response["data"]["errorMessage"]);
    }

    // Cancel payment link
    public function testCancelPaymentLinkSucceeds() {
        $this->setUpCancelPaymentLink();
        $response = $this->paymentLinkService->cancelPaymentLink([
            "location" => "https://sandbox.kopokopo.com/api/v2/payment_links/d5963294-22bf-46f6-a13c-b6a9951b4312",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("Payment link cancelled.", $response["message"]);
    }

    public function testCancelPaymentLinkWithoutLocationFails()
    {
        $this->setUpCancelPaymentLink();
        $response = $this->paymentLinkService->cancelPaymentLink([
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the location", $response["data"]);
    }

    public function testCancelPaymentLinkWithoutAccessTokenFails()
    {
        $this->setUpCancelPaymentLink();
        $response = $this->paymentLinkService->cancelPaymentLink([
            "location" => "https://sandbox.kopokopo.com/api/v2/payment_links/d5963294-22bf-46f6-a13c-b6a9951b4312",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accessToken", $response["data"]);
    }

    public function testCancelPaymentLinkHandlesRequestExceptionsGracefully()
    {
        $this->setUpPaymentLinkRequestError();
        $response = $this->paymentLinkService->cancelPaymentLink([
            "location" => "https://sandbox.kopokopo.com/api/v2/payment_links/d5963294-22bf-46f6-a13c-b6a9951b4312",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("400", $response["data"]["errorCode"]);
        $this->assertEquals("Till number is invalid", $response["data"]["errorMessage"]);
    }

    // Get payment link status
    public function testGetPaymentLinkStatusSucceeds()
    {
        $this->setUpGetPaymentLinkStatus();
        $result = json_decode(file_get_contents(__DIR__."/Mocks/paymentLinks/paymentLinkStatus.json"), true)["data"];
        $response = $this->paymentLinkService->getStatus([
            "location" => "https://sandbox.kopokopo.com/api/v2/payment_links/d5963294-22bf-46f6-a13c-b6a9951b4312",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $formattedResult = [
            "id" => $result["id"],
            "type" => $result["type"],
            "status" => $result["attributes"]["status"],
            "currency" => $result["attributes"]["currency"],
            "amount" => $result["attributes"]["amount"],
            "tillName" => $result["attributes"]["till_name"],
            "tillNumber" => $result["attributes"]["till_number"],
            "paymentReference" => $result["attributes"]["payment_reference"],
            "note" => $result["attributes"]["note"],
            "createdAt" => $result["attributes"]["created_at"],
            "paymentLink" => K2Utils::deepCamelizeKeys($result["attributes"]["payment_link"]),
            "errors" => $result["attributes"]["errors"],
            "metadata" => $result["attributes"]["metadata"],
            "callbackUrl" => $result["attributes"]["_links"]["callback_url"],
            "linkSelf" => $result["attributes"]["_links"]["self"],
        ];

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals($formattedResult, $response["data"]);
    }

    public function testGetPaymentLinkStatusWithoutLocationFails()
    {
        $this->setUpGetPaymentLinkStatus();
        $response = $this->paymentLinkService->getStatus([
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the location", $response["data"]);
    }

    public function testGetPaymentLinkStatusWithoutAccessTokenFails()
    {
        $this->setUpGetPaymentLinkStatus();
        $response = $this->paymentLinkService->getStatus([
            "location" => "https://sandbox.kopokopo.com/api/v2/payment_links/d5963294-22bf-46f6-a13c-b6a9951b4312",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accessToken", $response["data"]);
    }
}
