<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\FirstDataLatvia\Responses;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\FirstDataLatvia\Gateway;

/**
 * Class AuthorizeResponse
 * @package Omnipay\FirstDataLatvia\Responses
 */
class AuthorizeResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * @return string|null
     */
    public function getTransactionReference(): ?string
    {
        return $this->data[Gateway::TRANSACTION_ID_KEY] ?? null;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return !isset($this->data['error']);
    }

    /**
     * @return bool
     */
    public function isRedirect(): bool
    {
        return $this->isSuccessful();
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->getClientEndpoint() . '?' . http_build_query(['trans_id' => $this->getTransactionReference()]);
    }
}
