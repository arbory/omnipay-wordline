<?php

namespace Omnipay\FirstDataLatvia\Messages;

class RefundRequest extends AbstractRequest
{
    /**
     * amount - optional parameter – refund transaction amount in fractional units (up to 12 characters) . If not specified, full original transaction amount will be refunded.
     * @return array
     */
    public function getData()
    {
        $data = [
            'command'           => 'k',
            'trans_id'          => $this->getTransactionReference(),
            'amount'            => $this->getAmountInteger()
        ];

        return $data;
    }

    /**
     * @param       $httpResponse
     * @param array $data
     * @return RefundResponse
     */
    public function createResponse($httpResponse, array $data)
    {
        return $purchaseResponseObj = new RefundResponse($this, $data);
    }

}
