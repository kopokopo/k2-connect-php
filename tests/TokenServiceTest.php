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
use Kopokopo\SDK\TokenService;

class TokenServiceTest extends TestCase
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
        *    getToken() setup
        */

        // getToken() response headers
        $tokenHeaders = file_get_contents(__DIR__.'/Mocks/tokenHeaders.json');
        // json response to be returned
        $tokenResponse= file_get_contents(__DIR__.'/Mocks/tokenResponse.json');

        // Create an instance of MockHandler for returning responses for getToken()
        $tokenRequestMock = new MockHandler([
            new Response(200, [], $tokenResponse),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $tokenRequestHandler = HandlerStack::create($tokenRequestMock);

        // Create a new instance of client using the getToken() handler
        $tokenRequestClient = new Client(['handler' => $tokenRequestHandler]);

        // Use $tokenRequestClient to create an instance of the TokenService() class
        $this->tokenRequestClient = new TokenService($tokenRequestClient, $options);

        /*
        *    revokeToken() setup
        */

        // Create an instance of MockHandler for returning responses for revokeToken()
        $revokeMock = new MockHandler([
            new Response(200, []),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $revokeHandler = HandlerStack::create($revokeMock);

        // Create a new instance of client using the revokeToken() handler
        $tokenRevokeClient = new Client(['handler' => $revokeHandler]);

        // Use the $tokenRevokeClient to create an instance of the TokenService() class
        $this->tokenRevokeClient = new TokenService($tokenRevokeClient, $options);

        /*
        *    introspectToken() setup
        */

        // json response to be returned
        $introspectBody = file_get_contents(__DIR__.'/Mocks/tokenIntrospect.json');

        // Create an instance of MockHandler for returning responses for introspectToken()
        $introspectMock = new MockHandler([
            new Response(200, [], $introspectBody),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $introspectHandler = HandlerStack::create($introspectMock);

        // Create a new instance of client using the introspectToken() handler
        $introspectClient = new Client(['handler' => $introspectHandler]);

        // Use the $introspectClient to create an instance of the TokenService() class
        $this->introspectClient = new TokenService($introspectClient, $options);

        /*
        *    infoToken() setup
        */

        // json response to be returned
        $infoBody = file_get_contents(__DIR__.'/Mocks/tokenInfo.json');

        // Create an instance of MockHandler for returning responses for infoToken()
        $infoMock = new MockHandler([
            new Response(200, [], $infoBody),
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
        ]);

        // Assign the instance of MockHandler to a HandlerStack
        $infoHandler = HandlerStack::create($infoMock);

        // Create a new instance of client using the infoToken() handler
        $infoClient = new Client(['handler' => $infoHandler]);

        // Use the $infoClient to create an instance of the TokenService() class
        $this->infoClient = new TokenService($infoClient, $options);
    }

    public function testGetTokenSucceeds()
    {
        $response = $this->tokenRequestClient->getToken();

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testRevokeTokenSucceeds()
    {
        $response = $this->tokenRevokeClient->revokeToken([
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testRevokeTokenWithNoAccessTokenFails()
    {
        $response = $this->tokenRevokeClient->revokeToken([]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }

    public function testIntrospectTokenSucceeds()
    {
        $response = $this->introspectClient->introspectToken([
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testIntrospectTokenWithNoAccessTokenFails()
    {
        $response = $this->introspectClient->introspectToken([]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }

    public function testInfoTokenSucceeds()
    {
       $response = $this->infoClient->infoToken([
            'accessToken' => 'myRand0mAcc3ssT0k3n',
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    public function testInfoTokenWithNoAccessTokenFails()
    {
        $response = $this->infoClient->infoToken([]);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('You have to provide the accessToken', $response['data']);
    }
}
