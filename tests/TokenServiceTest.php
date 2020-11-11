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
    }

    public function testGetTokenSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->tokenRequestClient->getToken()
        );
    }
}
