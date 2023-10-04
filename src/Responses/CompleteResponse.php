<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\Worldline\Responses;

/**
 * Class CompleteResponse
 * @package Omnipay\Worldline\Responses
 */
class CompleteResponse extends AbstractResponse
{
    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return (!isset($this->data['error'])) // make it backwards compatible
            &&
            (
                isset($this->data[self::RESULT_CODE_KEY])
                &&
                $this->data[self::RESULT_CODE_KEY] === self::RESULT_SUCCESS_CODE
            );
    }

    /**
     * @return string|null
     */
    public function getTransactionReference(): ?string
    {
        return $this->request->getTransactionReference();
    }
}
