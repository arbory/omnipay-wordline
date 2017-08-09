<?php

namespace Omnipay\FirstDataLatvia\Messages;

use Omnipay\Common\Message\RedirectResponseInterface;

class AuthorizeResponse extends AbstractResponse  implements RedirectResponseInterface
{

    public function getTransactionReference()
    {
        return $this->data['TRANSACTION_ID'];
    }

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData(){
        return null;
    }

    public function getRedirectUrl()
    {
       return $this->getClientEndpoint() . '?' . http_build_query(['trans_id' => $this->getTransactionReference()]);
    }

}