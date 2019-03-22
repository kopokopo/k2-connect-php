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
        switch ($this->data['topic']) {
            case 'transaction_received':
                switch ($this->data['event']['type']) {
                    case 'Buygoods Transaction':
                        return BuygoodsReceivedData::setData($this->data);
                        break;
                    case 'B2b Transaction':
                        return B2bReceivedData::setData($this->data);
                        break;
                    case 'Merchant to Merchant Transaction':
                        return M2mReceivedData::setData($this->data);
                        break;
                }
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
            case 'payment_request':
                return stkresultData::setData($this->data);

                break;
        }
    }
}
