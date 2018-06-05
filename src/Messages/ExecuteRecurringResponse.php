<?php

namespace Omnipay\FirstDataLatvia\Messages;

use Omnipay\Common\Message\RedirectResponseInterface;

class ExecuteRecurringResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function getTransactionReference()
    {
        if (isset($this->data['TRANSACTION_ID'])) {
            return $this->data['TRANSACTION_ID'];
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return !empty($this->data['RESULT']) && $this->data['RESULT'] === 'OK';
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * @return null
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->getClientEndpoint() . '?' . http_build_query(['trans_id' => $this->getTransactionReference()]);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        if (isset($this->data['RESULT_CODE'])) {
            $code = $this->data['RESULT_CODE'];
        } else {
            return 'Unknown error';
        }

        if ($code == '108') {
            return 'Merchant shall contact cardholder';
        } else if ($code == '114') {
            return 'Declined, merchant can retry transaction after 72 hours';
        } else if ($code == '180') {
            return 'Cardholder has requested blocking of recurring payment';
        } else if (substr($code, 0, 1) == '2') {
            return 'Regular payment has been deleted';
        }

        return 'Unknown error';
    }

}
