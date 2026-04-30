<?php

namespace Kopokopo\SDK\Data;

class ResultDataHandler
{
    public function sort($data)
    {
        // For results and status payloads
        switch ($data["type"]) {
            case "merchant_wallet":
                return MerchantWalletData::setData($data);
            case "merchant_bank_account":
                return MerchantBankAccountData::setData($data);
            case "send_money":
                return SendMoneyResultData::setData($data);
            case "incoming_payment":
                return StkData::setData($data);
            case "webhook_subscription":
                return WebhookSubscriptionData::setData($data);
            case "external_recipient":
                return ExternalRecipientData::setData($data);
            case "polling":
                return PollingData::setData($data);
            case "reversal":
                return ReversalData::setData($data);
            case "payment_link":
                return PaymentLinkData::setData($data);
        }
    }
}