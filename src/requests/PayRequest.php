<?php

namespace Kopokopo\SDK\Requests;

class PayRequest extends BaseRequest
{
    public function getDestinationRef()
    {
        return $this->getRequestData('destinationReference');
    }

    public function getDestinationType()
    {
        return $this->getRequestData('destinationType');
    }

    public function getAmount()
    {
        return $this->getRequestData('amount');
    }

    public function getCurrency()
    {
        return $this->getRequestData('currency');
    }

    public function getDescription()
    {
        return $this->getRequestData('description');
    }

    public function getCategory()
    {
        if (!isset($this->data['category'])) {
            return null;
        }

        return $this->getRequestData('category');
    }

    public function getTags()
    {
        if (!isset($this->data['tags'])) {
            return null;
        }

        return $this->getRequestData('tags');
    }

    public function getUrl()
    {
        return $this->getRequestData('callbackUrl');
    }

    public function getMetadata()
    {
        if (!isset($this->data['metadata'])) {
            return null;
        }

        return $this->getRequestData('metadata');
    }

    public function getPayBody()
    {
        return [
            'destination_reference' => $this->getDestinationRef(),
            'destination_type' => $this->getDestinationType(),
            'amount' => [
                'currency' => $this->getCurrency(),
                'value' => $this->getAmount(),
            ],
            'description' => $this->getDescription(),
            'category' => $this->getCategory(),
            'tags' => $this->getTags(),
            'metadata' => $this->getMetadata(),
            '_links' => [
                'callback_url' => $this->getUrl(),
            ],
        ];
    }
}
