<?php

namespace Omnipay\FirstDataLatvia\Messages;

class CaptureRequest extends AbstractRequest
{
    public function getData()
    {
        $data = [
            'command'           => 't',
            'msg_type'          => 'DMS',
            'trans_id'          => $this->getTransactionReference(),
            'amount'            => $this->getAmountInteger(),
            'currency'          => $this->getCurrencyNumeric(),
            'client_ip_addr'    => $this->getClientIp()
        ];

        return $data;
    }

    public function createResponse($httpResponse, array $data)
    {
        return $purchaseResponseObj = new CaptureResponse($this, $data);
    }
}