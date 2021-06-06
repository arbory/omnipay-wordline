<?php

namespace Omnipay\FirstDataLatvia\Messages;

class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $data = [
            'command'           => 'a',
            'msg_type'          => 'DMS',
            'amount'            => $this->getAmountInteger(),
            'currency'          => $this->getCurrencyNumeric(),
            'client_ip_addr'    => $this->getClientIp(),
            'description'       => $this->getDescription(),
            'language'          => $this->getLanguage(),
        ];

        return $data;
    }

    public function createResponse($httpResponse, array $data)
    {
        return $purchaseResponseObj = new PurchaseResponse($this, $data);
    }
}