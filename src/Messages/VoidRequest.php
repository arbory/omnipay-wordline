<?php

namespace Omnipay\FirstDataLatvia\Messages;

/**
 * Class VoidRequest
 *
 * @package Omnipay\FirstDataLatvia\Messages
 */
class VoidRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData()
    {
        $data = [
            'command'           => 'r',
            'amount'            => $this->getAmountInteger(),
            'trans_id'          => $this->getTransactionReference()
        ];

        return $data;
    }

    /**
     * @param       $httpResponse
     * @param array $data
     * @return VoidResponse
     */
    public function createResponse($httpResponse, array $data)
    {
        return $purchaseResponseObj = new VoidResponse($this, $data);
    }
}