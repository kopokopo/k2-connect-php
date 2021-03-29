<?php

namespace Kopokopo\SDK;

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

            return $this->tokenSuccess($response);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $this->error($e->getResponse()->getBody()->getContents());
        } catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}
