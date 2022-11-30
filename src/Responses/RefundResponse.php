<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\Worldline\Responses;

/**
 * Class RefundResponse
 * @package Omnipay\Worldline\Responses
 */
class RefundResponse extends AbstractResponse
{
    protected const REFUND_TRANS_ID_KEY = 'REFUND_TRANS_ID';

    /**
     * @return string|null
     */
    public function getRefundTransaction(): ?string
    {
        return $this->data[self::REFUND_TRANS_ID_KEY] ?? null;
    }
}
