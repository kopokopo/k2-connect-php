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
    public function setup()
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

        // Create an instance of MockHandler for returning responses for getToken()
        $tokenRequestMock = new MockHandler([
            new Response(200, json_decode($tokenHeaders, true)),
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
        $introspectBody = file_get_contents(__DIR__.'/Mocks/token-introspect.json');

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
        $infoBody = file_get_contents(__DIR__.'/Mocks/token-info.json');

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
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->tokenRequestClient->getToken()
        );
    }

    public function testRevokeTokenSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->tokenRevokeClient->revokeToken([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testRevokeTokenWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->tokenRevokeClient->revokeToken([])
        );
    }

    public function testIntrospectTokenSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->introspectClient->introspectToken([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testIntrospectTokenWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->introspectClient->introspectToken([])
        );
    }

    public function testInfoTokenSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->infoClient->infoToken([
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ])
        );
    }

    public function testInfoTokenWithNoAccessTokenFails()
    {
        $this->assertArraySubset(
            ['data' => 'You have to provide the accessToken'],
            $this->infoClient->infoToken([])
        );
    }
}
