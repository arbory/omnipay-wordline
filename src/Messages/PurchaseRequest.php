<?php

namespace Omnipay\FirstDataLatvia\Messages;

class PurchaseRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData()
    {
        $data = [
            'command'           => 'v',
            'amount'            => $this->getAmountInteger(),
            'currency'          => $this->getCurrencyNumeric(),
            'client_ip_addr'    => $this->getClientIp(),
            'description'       => $this->getDescription(),
            'language'          => $this->getLanguage(),
        ];

        return $data;
    }

    /**
     * @param       $httpResponse
     * @param array $data
     * @return PurchaseResponse
     */
    public function createResponse($httpResponse, array $data)
    {
        return $purchaseResponseObj = new PurchaseResponse($this, $data);
    }
}