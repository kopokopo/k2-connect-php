<?php

namespace Kopokopo\SDK;

class TokenService extends Service
{
    public function getToken()
    {
        $grantType = 'client_credentials';

        $requestData = [
            'client_id' => $this->clientSecret,
            'client_secret' => $this->clientSecret,
            'grant_type' => $grantType,
        ];

        $response = $this->client->post('oauth', ['form_params' => $requestData]);

        return $this->success($response);
    }
}
