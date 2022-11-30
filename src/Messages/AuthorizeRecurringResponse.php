<?php

namespace Omnipay\Worldline\Messages;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Worldline\Responses\AbstractResponse;

class AuthorizeRecurringResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * @return null|string
     */
    public function getTransactionReference()
    {
        if (isset($this->data['TRANSACTION_ID'])) {
            return trim($this->data['TRANSACTION_ID']);
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return false; //needs redirect
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        //on blacklisted client ip error will be returned
        if (isset($this->data['error'])) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * @return array|null
     */
    public function getRedirectData()
    {
        return null; // GET redirect does not need data
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->getClientEndpoint() . '?' . http_build_query(['trans_id' => $this->getTransactionReference()]);
    }
}
