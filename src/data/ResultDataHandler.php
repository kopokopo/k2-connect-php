<?php

namespace Kopokopo\SDK\Data;

class ResultDataHandler
{
    public function sort($data)
    {
        // For results and status payloads
        switch ($data['type']) {
            case 'merchant_wallet':
                return MerchantWalletData::setData($data);
            break;
            case 'merchant_bank_account':
                return MerchantBankAccountData::setData($data);
            break;
            case 'settlement_transfer':
                return SettlementTransferResultData::setData($data);
            break;
            case 'incoming_payment':
                return StkData::setData($data);
            break;
            case 'webhook_subscription':
                return WebhookSubscriptionData::setData($data);
            break;
            case 'pay_recipient':
                return PayRecipientData::setData($data);
            break;
            case 'payment':
                return PaymentData::setData($data);
            break;
            case 'polling':
                return PollingData::setData($data);
            break;
            case 'transaction_sms_notification':
                return TransactionSmsNotificationData::setData($data);
            break;
        }
    }
}
