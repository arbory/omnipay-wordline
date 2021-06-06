<?php

namespace Omnipay\FirstDataLatvia\Messages;

class CompleteRequest extends AbstractRequest
{

    public function getData()
    {
        $data = [
            'command'           => 'c',
            'trans_id'          => $this->getTransactionReference(),
            'client_ip_addr'    => $this->getClientIp()
        ];

        return $data;
    }

    public function createResponse($httpResponse, array $data)
    {
        return $purchaseResponseObj = new CompleteResponse($this, $data);
    }
}