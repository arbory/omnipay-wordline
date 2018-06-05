<?php

namespace Omnipay\FirstDataLatvia\Messages;

class AuthorizeRecurringRequest extends AbstractRequest
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
            'msg_type' => 'AUTH',
            'biller_client_id' => $this->getUniqueId(), // recurring payment identifier, mandatory (up to 30 characters)
            'perspayee_expiry' => $this->getExpiryDate(), // preferred deadline for a Recurring payment, mandatory (MMYY),
            'perspayee_gen' => '1'
        ];

        return $data;
    }

    /**
     * @param $httpResponse
     * @param array $data
     * @return RecurringAuthorizeResponse
     */
    public function createResponse($httpResponse, array $data)
    {
        return new AuthorizeRecurringResponse($this, $data);
    }

    /**
     * @return string
     */
    public function getUniqueId()
    {
        return $this->getParameter('uniqueId');
    }

    /**
     * @param string $value
     */
    public function setUniqueId($value)
    {
        $this->setParameter('uniqueId', $value);
    }

    /**
     * @return string
     */
    public function getExpiryDate()
    {
        return $this->getParameter('expiryDate');
    }

    /**
     * @param string $value
     */
    public function setExpiryDate($value)
    {
        $this->setParameter('expiryDate', $value);
    }
}