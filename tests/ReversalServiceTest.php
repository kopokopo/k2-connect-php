<?php

namespace Kopokopo\SDK\Tests;
require "vendor/autoload.php";

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Kopokopo\SDK\Helpers\K2Utils;
use Kopokopo\SDK\ReversalService;
use PHPUnit\Framework\TestCase;

class ReversalServiceTest extends TestCase
{
    private ReversalService $reversalService;

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
        $reversalHeaders = file_get_contents((__DIR__."/Mocks/reversals/reversalHeaders.json"));
        $reversalMock = new MockHandler([ new Response(200, json_decode($reversalHeaders, true))]);
        $reversalHandler = HandlerStack::create($reversalMock);
        $reversalClient = new Client(["handler" => $reversalHandler]);
        $this->reversalService = new ReversalService($reversalClient, $k2Options);
    }

    private function setUpGetReversalStatus(): void
    {
        $k2Options = $this->getK2Options();
        $reversalStatus = file_get_contents((__DIR__."/Mocks/reversals/reversalStatus.json"));
        $reversalMock = new MockHandler([ new Response(200, [], $reversalStatus)]);
        $reversalHandler = HandlerStack::create($reversalMock);
        $reversalClient = new Client(["handler" => $reversalHandler]);
        $this->reversalService = new ReversalService($reversalClient, $k2Options);
    }

    // Initiate reversal
    public function testInitiateReversalSucceeds(): void
    {
        $response = $this->reversalService->initiateReversal([
            "transactionReference" => "J82K922T92",
            "reason" => "Double payment",
            "metadata" => [
                "notes" => "John Doe reached out due to double payment.",
                "zendeskTicket" => "ZND2393334",
            ],
            "callbackUrl" => "http://localhost:8000/reversal_results",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("https://sandbox.kopokopo.com/api/v2/reversals/b42e022a-594e-47c3-b717-a259a5797312", $response["location"]);
    }

    public function testInitiateReversalWithoutMetadataSucceeds(): void
    {
        $response = $this->reversalService->initiateReversal([
            "transactionReference" => "J82K922T92",
            "reason" => "Double payment",
            "callbackUrl" => "http://localhost:8000/reversal_results",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals("https://sandbox.kopokopo.com/api/v2/reversals/b42e022a-594e-47c3-b717-a259a5797312", $response["location"]);
    }

    public function testInitiateReversalWithoutTransactionReferenceFails(): void
    {
        $response = $this->reversalService->initiateReversal([
            "reason" => "Double payment",
            "metadata" => [
                "notes" => "John Doe reached out due to double payment.",
                "zendeskTicket" => "ZND2393334",
            ],
            "callbackUrl" => "http://localhost:8000/reversal_results",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the transactionReference", $response["data"]);
    }

    public function testInitiateReversalWithoutReasonFails(): void
    {
        $response = $this->reversalService->initiateReversal([
            "transactionReference" => "J82K922T92",
            "metadata" => [
                "notes" => "John Doe reached out due to double payment.",
                "zendeskTicket" => "ZND2393334",
            ],
            "callbackUrl" => "http://localhost:8000/reversal_results",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the reason", $response["data"]);
    }

    public function testInitiateReversalWithoutCallbackUrlFails(): void
    {
        $response = $this->reversalService->initiateReversal([
            "transactionReference" => "J82K922T92",
            "reason" => "Double payment",
            "metadata" => [
                "notes" => "John Doe reached out due to double payment.",
                "zendeskTicket" => "ZND2393334",
            ],
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the callbackUrl", $response["data"]);
    }

    public function testInitiateReversalWithoutAccessTokenFails(): void
    {
        $response = $this->reversalService->initiateReversal([
            "transactionReference" => "J82K922T92",
            "reason" => "Double payment",
            "metadata" => [
                "notes" => "John Doe reached out due to double payment.",
                "zendeskTicket" => "ZND2393334",
            ],
            "callbackUrl" => "http://localhost:8000/reversal_results",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accessToken", $response["data"]);
    }

    public function testInitiateReversalHandlesRequestExceptionsGracefully(): void {
        $k2Options = $this->getK2Options();
        $reversalError = file_get_contents(__DIR__."/Mocks/reversals/reversalError.json");
        $reversalMock = new MockHandler([new Response(400, [], $reversalError),]);
        $reversalHandler = HandlerStack::create($reversalMock);
        $reversalClient = new Client(["handler" => $reversalHandler]);
        $this->reversalService = new ReversalService($reversalClient, $k2Options);
        $response = $this->reversalService->initiateReversal([
            "transactionReference" => "Invalid transaction reference",
            "reason" => "Double payment",
            "metadata" => [
                "notes" => "John Doe reached out due to double payment.",
                "zendeskTicket" => "ZND2393334",
            ],
            "callbackUrl" => "http://localhost:8000/reversal_results",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("400", $response["data"]["errorCode"]);
        $this->assertEquals("Transaction reference invalid", $response["data"]["errorMessage"]);
    }

    // Query reversal status
    public function testGetReversalRequestStatusSucceeds(): void
    {
        $this->setUpGetReversalStatus();
        $result = json_decode(file_get_contents(__DIR__."/Mocks/reversals/reversalStatus.json"), true)["data"];
        $response = $this->reversalService->getStatus([
            "location" => "https://sandbox.kopokopo.com/api/v2/reversals/b42e022a-594e-47c3-b717-a259a5797312",
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $formattedResult = [
            "id" => $result["id"],
            "type" => $result["type"],
            "transactionReference" => $result["attributes"]["transaction_reference"],
            "status" => $result["attributes"]["status"],
            "reason" => $result["attributes"]["reason"],
            "reversalBulkPayment" => K2Utils::deepCamelizeKeys($result["attributes"]["reversal_bulk_payment"]),
            "errors" => $result["attributes"]["errors"],
            "metadata" => $result["attributes"]["metadata"],
            "createdAt" => $result["attributes"]["created_at"],
            "callbackUrl" => $result["attributes"]["_links"]["callback_url"],
            "linkSelf" => $result["attributes"]["_links"]["self"],
        ];

        $this->assertEquals("success", $response["status"]);
        $this->assertEquals($formattedResult, $response["data"]);
    }

    public function testGetReversalRequestStatusWithoutLocationFails(): void
    {
        $this->setUpGetReversalStatus();
        $response = $this->reversalService->getStatus([
            "accessToken" => "myRand0mAcc3ssT0k3n",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the location", $response["data"]);
    }

    public function testGetReversalRequestStatusWithoutAccessTokenFails(): void
    {
        $this->setUpGetReversalStatus();
        $response = $this->reversalService->getStatus([
            "location" => "https://sandbox.kopokopo.com/api/v2/reversals/b42e022a-594e-47c3-b717-a259a5797312",
        ]);

        $this->assertEquals("error", $response["status"]);
        $this->assertEquals("You have to provide the accessToken", $response["data"]);
    }
}