<?php

namespace Kopokopo\SDK\Data;

class FailedResponseData
{
    protected $data;

    public function setErrorData($errorResponse)
    {
        $this->data['errorCode'] = $errorResponse['error_code'];
        $this->data['errorMessage'] = $errorResponse['error_message'];

        return $this->data;
    }

    public function setTokenErrorData($errorResponse)
    {
        $this->data['error'] = $errorResponse['error'];
        $this->data['errorDescription'] = $errorResponse['error_description'];

        return $this->data;
    }
}
