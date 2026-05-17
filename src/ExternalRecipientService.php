<?php

namespace Kopokopo\SDK;

use Kopokopo\SDK\Requests\ExternalMobileWalletRequest;
use Kopokopo\SDK\Requests\ExternalBankAccountRequest;
use Kopokopo\SDK\Requests\ExternalTillRequest;
use Kopokopo\SDK\Requests\ExternalPaybillRequest;
use Kopokopo\SDK\Data\FailedResponseData;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\BadResponseException;
use Exception;


class ExternalRecipientService extends Service
{
    public function addExternalRecipient($options)
    {
        try {
            if (!isset($options['type'])) {
                throw new \InvalidArgumentException('You have to provide the type');
            } elseif ($options['type'] === 'bank_account') {
                $externalRecipientRequest = new ExternalBankAccountRequest($options);
            } elseif ($options['type'] === 'till') {
                $externalRecipientRequest = new ExternalTillRequest($options);
            } elseif ($options['type'] === 'paybill') {
                $externalRecipientRequest = new ExternalPaybillRequest($options);
            } elseif ($options['type'] === 'mobile_wallet') {
                $externalRecipientRequest = new ExternalMobileWalletRequest($options);
            } else{
                throw new \InvalidArgumentException('Invalid recipient type');
            }

            $response = $this->client->post('external_recipients', ['body' => json_encode($externalRecipientRequest->getExternalRecipientBody()), 'headers' => $externalRecipientRequest->getHeaders()]);

            return $this->postSuccess($response);
        } catch (BadResponseException $e) {
            $dataHandler = new FailedResponseData();
            return $this->error($dataHandler->setErrorData($e));
        } catch (GuzzleException | Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
