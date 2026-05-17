<?php

namespace Kopokopo\SDK;

use Kopokopo\SDK\Requests\StatusRequest;
use Kopokopo\SDK\Data\DataHandler;
use Kopokopo\SDK\Data\FailedResponseData;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Exception;

abstract class Service
{
    protected $client;
    protected $clientId;
    protected $clientSecret;
    protected $apiKey;

    /**
    * @param Client $client
    * @param array $options
    */
    public function __construct(Client $client, array $options)
    {
        $this->client = $client;
        $this->clientId = $options['clientId'];
        $this->clientSecret = $options['clientSecret'];
        $this->apiKey = $options['apiKey'];
    }

    /*
    * @param string|array $data
    * @return array
    */
    protected static function error(string|array $data): array
    {
        return [
            'status' => 'error',
            'data' => $data,
        ];
    }

    /*
    * @param Response $data
    * @return array
    */
    protected static function postSuccess(ResponseInterface $data): array
    {
        return [
            'status' => 'success',
            'location' => $data->getHeaders()['location'][0],
        ];
    }

    /*
    * @param string|array $data
    * @return array
    */
    protected static function success(string|array $data): array
    {
        return [
            'status' => 'success',
            'data' => $data,
        ];
    }

    /**
    * @param array $options
    */
    public function getStatus(array $options): array
    {
        try {
            $status = new StatusRequest($options);

            $response = $this->client->get($status->getLocation(), ['headers' => $status->getHeaders()]);

            $dataHandler = new DataHandler(json_decode($response->getBody()->getContents(), true));

            return $this->success($dataHandler->dataHandlerSort());
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setErrorData($e));
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
