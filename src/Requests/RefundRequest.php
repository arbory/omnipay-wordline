<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\FirstDataLatvia\Requests;

use Omnipay\FirstDataLatvia\Responses\RefundResponse;

/**
 * Class RefundRequest
 * @package Omnipay\FirstDataLatvia\Requests
 */
class RefundRequest extends AbstractRequest
{
    /**
     * amount - optional parameter – refund transaction amount in fractional units (up to 12 characters).
     * If not specified, full original transaction amount will be refunded.
     * @return array
     * @throws \Exception
     */
    public function getData(): array
    {
        return [
            'command' => 'k',
            'amount' => $this->getAmountInteger(),
            'trans_id' => $this->getTransactionReference(),
        ];
    }

    /**
     * @param $httpResponse
     * @param array $data
     * @return RefundResponse
     * @throws \Exception
     */
    public function createResponse($httpResponse, array $data): RefundResponse
    {
        return new RefundResponse($this, $data);
    }
}
