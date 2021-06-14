<?php

namespace Kopokopo\SDK\Data;

class TokenData
{
    protected $data;

    public function setGetTokenData($response)
    {
        $this->data['accessToken'] = $response['access_token'];
        $this->data['tokenType'] = $response['token_type'];
        $this->data['expiresIn'] = $response['expires_in'];
        $this->data['createdAt'] = $response['created_at'];

        return $this->data;
    }

    public function setIntrospectTokenData($response)
    {
        $this->data['active'] = $response['active'];
        $this->data['scope'] = $response['scope'];
        $this->data['clientId'] = $response['client_id'];
        $this->data['tokenType'] = $response['token_type'];
        $this->data['exp'] = $response['exp'];
        $this->data['iat'] = $response['iat'];

        return $this->data;
    }

    public function setInfoTokenData($response)
    {
        $this->data['resourceOwnerId'] = $response['resource_owner_id'];
        $this->data['scope'] = $response['scope'];
        $this->data['expiresIn'] = $response['expires_in'];
        $this->data['applicationId'] = $response['application']['uid'];
        $this->data['createdAt'] = $response['created_at'];

        return $this->data;
    }
}
