<?php

namespace Omnipay\FirstDataLatvia\Messages;

use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse  implements RedirectResponseInterface
{

    public function getTransactionReference()
    {
        if(isset($this->data['TRANSACTION_ID'])){
            return $this->data['TRANSACTION_ID'];
        }
        return null;
    }

    public function isSuccessful()
    {
        return false; //needs redirect
    }

    public function isRedirect()
    {
        //on blacklisted client ip error will be returned
        if(isset($this->data['error'])){
            return false;
        }
        
        return true;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData(){
        return null; // GET redirect does not need data
    }

    public function getRedirectUrl()
    {
       return $this->getClientEndpoint() . '?' . http_build_query(['trans_id' => $this->getTransactionReference()]);
    }

}
