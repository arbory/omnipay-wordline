<?php

namespace Omnipay\FirstDataLatvia\Messages;

class CloseDayRequest extends AbstractRequest
{
    public function getData()
    {
        $data = [
            'command'           => 'b'
        ];

        return $data;
    }

    public function createResponse($httpResponse, array $data)
    {
        return $purchaseResponseObj = new CloseDayResponse($this, $data);
    }
}