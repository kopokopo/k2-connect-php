<?php

namespace Kopokopo\SDK\Data;

class DataHandler
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function dataHandlerSort()
    {
        if (isset($this->data['topic'])) {
            // Webhooks
            switch ($this->data['topic']) {
                case 'buygoods_transaction_received':
                    return BuygoodsReceivedData::setData($this->data);
                    break;
                case 'b2b_transaction_received':
                    return B2bReceivedData::setData($this->data);
                    break;
                case 'm2m_transaction_received':
                    return M2mReceivedData::setData($this->data);
                    break;
                case 'buygoods_transaction_reversed':
                    return BuygoodsReversedData::setData($this->data);
                    break;
                case 'settlement_transfer_completed':
                    return TransferCompletedData::setData($this->data);
                    break;
                case 'customer_created':
                    return CustomerCreatedData::setData($this->data);
                    break;
            }
        } else {
            // Result and Status Payloads
            $resultDataHandler = new ResultDataHandler;
            return $resultDataHandler->sort($this->data['data']);
        }        
    }
}
