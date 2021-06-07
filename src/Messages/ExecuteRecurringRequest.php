<?php

namespace Omnipay\FirstDataLatvia\Messages;

class ExecuteRecurringRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData()
    {
        $data = [
            'command' => 'e', // request for executing subsequent recurring transaction
            'amount' => $this->getAmountInteger(), // transaction amount in minor units, mandatory (up to 12 digits)
            'currency' => $this->getCurrencyNumeric(), // transaction currency code (ISO 4217), mandatory, (3 digits)
            'client_ip_addr' => $this->getClientIp(), // client’s IP address (the same IP address which was provided when registered recurring payment)
            'desc' => $this->getDescription(), // transaction details, mandatory (up to 125 characters)
            'biller_client_id' => $this->getUniqueId(), // recurring payment identifier, mandatory (up to 30 characters)
        ];

        return $data;
    }

    /**
     * @param $httpResponse
     * @param array $data
     * @return ExecuteRecurringResponse
     */
    public function createResponse($httpResponse, array $data)
    {
        return $purchaseResponseObj = new ExecuteRecurringResponse($this, $data);
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
}