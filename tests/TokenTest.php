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

class TokenTest extends TestCase
{
    public function setup()
    {
        $this->clientId = 'your_client_id';
        $this->clientSecret = '10af7ad062a21d9c841877f87b7dec3dbe51aeb3';

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
        $this->tokenRequestClient = new TokenService($tokenRequestClient, $this->clientId, $this->clientSecret);
    }

    public function testGetTokenSucceeds()
    {
        $this->assertArraySubset(
            ['status' => 'success'],
            $this->tokenRequestClient->getToken()
        );
    }
}
