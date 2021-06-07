<?php

namespace Omnipay\FirstDataLatvia\Messages;

class CaptureResponse extends AbstractResponse
{
    /**
     * Use only RESULT data to determine transactions state
     * Other fields are for debugging and logging!
     * This is from Payeezy IP admin manual
     * @return bool
     */
    public function isSuccessful()
    {
        if (isset($this->data['RESULT']) && trim($this->data['RESULT']) === 'OK') {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->data['RESULT'] ?? $this->data['RESULT'];
    }



}