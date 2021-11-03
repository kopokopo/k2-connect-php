<?php

namespace Kopokopo\SDK\Data;

class FailedResponseData
{
    protected $data;

    public function setErrorData($e){
        if ($e->hasResponse()) {
            $response = $e->getResponse();

            if(empty($response->getBody()) || empty(json_decode($response->getBody(), true)['error_message'])) {
                $this->data['errorCode'] = $response->getStatusCode();
                $this->data['errorMessage'] = empty($response->getBody()) ? $response->getReasonPhrase() : $response->getBody();

            } else {
                $errorPayload = json_decode($e->getResponse()->getBody(), true);

                $this->data['errorCode'] = $errorPayload['error_code'];
                $this->data['errorMessage'] = $errorPayload['error_message'];
            }
        } else {
            $this->data['errorCode'] = '';
            $this->data['errorMessage'] = 'An unknown error occurred';
        }
        return $this->data;
    }

    public function setTokenErrorData($e){
        if ($e->hasResponse()) {
            $response = $e->getResponse();

            if(empty($response->getBody()) || empty(json_decode($response->getBody(), true)['error_description'])) {
                $this->data['error'] = $response->getStatusCode();
                $this->data['errorDescription'] = empty($response->getBody()) ? $response->getReasonPhrase() : $response->getBody();

            } else {
                $errorPayload = json_decode($e->getResponse()->getBody(), true);

                $this->data['error'] = $errorPayload['error'];
                $this->data['errorDescription'] = $errorPayload['error_description'];
            }
        } else {
            $this->data['error'] = '';
            $this->data['errorDescription'] = 'An unknown error occurred';
        }
        return $this->data;
    }
}
