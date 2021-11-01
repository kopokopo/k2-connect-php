<?php

namespace Kopokopo\SDK;
use Kopokopo\SDK\Data\TokenData;
use Kopokopo\SDK\Data\FailedResponseData;
use Kopokopo\SDK\Requests\TokenRequest;

class TokenService extends Service
{
    public function getToken()
    {
        $grantType = 'client_credentials';

        $requestData = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => $grantType,
        ];

        try {
            $response = $this->client->post('oauth/token', ['form_params' => $requestData]);

            $dataHandler = new TokenData();

            return $this->success($dataHandler->setGetTokenData(json_decode($response->getBody()->getContents(), true)));
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setTokenErrorData($e));
        } catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function revokeToken($options)
    {
        try {
            $tokenRequest = new TokenRequest($options);

            $requestData = [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'token' => $tokenRequest->getAccessToken(),
            ];
            
            $response = $this->client->post('oauth/revoke', ['form_params' => $requestData]);

            return $this->success($response->getBody()->getContents());
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setTokenErrorData($e));
        } catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function introspectToken($options)
    {
        try {
            $tokenRequest = new TokenRequest($options);

            $requestData = [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'token' => $tokenRequest->getAccessToken(),
            ];

            $response = $this->client->post('oauth/introspect', ['form_params' => $requestData]);

            $dataHandler = new TokenData();

            return $this->success($dataHandler->setIntrospectTokenData(json_decode($response->getBody()->getContents(), true)));
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setTokenErrorData($e));
        } catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function infoToken($options)
    {
        try {
            $tokenRequest = new TokenRequest($options);

            $response = $this->client->get('oauth/token/info', ['headers' => $tokenRequest->getHeaders()]);

            $dataHandler = new TokenData();

            return $this->success($dataHandler->setInfoTokenData(json_decode($response->getBody()->getContents(), true)));
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setTokenErrorData($e));
        } catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}
