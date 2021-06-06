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
            $code = intval($this->data['RESULT_CODE']);
        } else if (isset($this->data['error'])) {
            return $this->data['error'];
        } else {
            return 'Unknown error';
        }

        switch ($code) {
            case 100: return 'Decline (general, no comments)';
            case 101: return 'Decline, expired card';
            case 102: return 'Decline, suspected fraud';
            case 103: return 'Decline, card acceptor contact acquirer';
            case 104: return 'Decline, restricted card';
            case 105: return 'Decline, card acceptor call acquirer\'s security department';
            case 106: return 'Decline, allowable PIN tries exceeded';
            case 107: return 'Decline, refer to card issuer';
            case 108: return 'Decline, refer to card issuer\'s special conditions, merchant shall contact cardholder';
            case 109: return 'Decline, invalid merchant';
            case 110: return 'Decline, invalid amount';
            case 111: return 'Decline, invalid card number';
            case 112: return 'Decline, PIN data required';
            case 113: return 'Decline, unacceptable fee';
            case 114: return 'Declined, merchant can retry transaction after 72 hours';
            case 115: return 'Decline, requested function not supported';
            case 116: return 'Decline, not sufficient funds';
            case 117: return 'Decline, incorrect PIN';
            case 118: return 'Decline, no card record';
            case 119: return 'Decline, transaction not permitted to cardholder';
            case 120: return 'Decline, transaction not permitted to terminal';
            case 121: return 'Decline, exceeds withdrawal amount limit';
            case 122: return 'Decline, security violation';
            case 123: return 'Decline, exceeds withdrawal frequency limit';
            case 124: return 'Decline, violation of law';
            case 125: return 'Decline, card not effective';
            case 126: return 'Decline, invalid PIN block';
            case 127: return 'Decline, PIN length error';
            case 128: return 'Decline, PIN kay synch error';
            case 129: return 'Decline, suspected counterfeit card';
            case 180: return 'Cardholder has requested blocking of recurring payment or regular payment has been deleted';
            case 197: return 'Decline, call AmEx';
            case 198: return 'Decline, call Card Processing Centre';
        }

        if (substr($code, 0, 1) == '2') {
            return 'Regular payment has been deleted';
        }

        return 'Unknown error';
    }

}
