<?php

namespace Omnipay\FirstDataLatvia\Messages;

class ReversalRequest extends AbstractRequest
{
    /**
     * amount - reversal amount in minor values, mandatory (up to 12 characters) . Merchant
     * may or may not be able to return partial amount depending on capabilities of
     * its acquirer/processor. Please contact your acquirer/processor to clarify this capability.
     *
     * @return array
     */
    public function getData()
    {
        $data = [
            'command'           => 'r',
            'trans_id'          => $this->getTransactionReference(),
            'amount'            => $this->getAmountInteger()
        ];

        return $data;
    }

    /**
     * @param       $httpResponse
     * @param array $data
     * @return ReversalResponse
     */
    public function createResponse($httpResponse, array $data)
    {
        return $purchaseResponseObj = new ReversalResponse($this, $data);
    }

}