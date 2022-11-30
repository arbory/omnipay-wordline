<?php

namespace Omnipay\Worldline\Messages;

class OverwriteRecurringWithoutPayment extends AuthorizeRecurringRequest
{
    /**
     * @return array
     */
    public function getData()
    {
        $data = [
            'command' => 'p', // request for recurring payment registration (SMS transaction)
            'currency' => $this->getCurrencyNumeric(), // transaction currency code (ISO 4217), mandatory, (3 digits)
            'client_ip_addr' => $this->getClientIp(), // client’s IP address, mandatory (15 characters)
            'desc' => $this->getDescription(), // transaction details, mandatory (up to 125 characters)
            'language' => $this->getLanguage(), // authorization language identifier, optional (up to 32 characters)
            'biller_client_id' => $this->getUniqueId(), // payment identifier, mandatory (up to 30 characters)
            'perspayee_expiry' => $this->getExpiryDate(), // preferred deadline for a  payment, mandatory (MMYY),
            'perspayee_overwrite' => '1'
        ];

        return $data;
    }
}
