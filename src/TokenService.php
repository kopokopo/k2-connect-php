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

        $response = $this->client->post('oauth/token', ['form_params' => $requestData]);

        return $this->tokenSuccess($response);
    }
}
