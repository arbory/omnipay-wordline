<?php

namespace Omnipay\FirstDataLatvia\Messages;

use Omnipay\Common\Message\AbstractResponse as CommonAbstractResponse;
use Omnipay\FirstDataLatvia\Helpers\ResultCodeMessages;

abstract class AbstractResponse extends CommonAbstractResponse
{
    /**
     * @var string
     */
    protected $testClientEndpoint = 'https://secureshop-test.firstdata.lv/ecomm/ClientHandler';

    /**
     * @var string
     */
    protected $liveClientEndpoint = 'https://secureshop.firstdata.lv/ecomm/ClientHandler';

    protected $testMode = null;

    public function setTestMode(bool $value){
        $this->testMode = $value;
    }

    public function getTestMode(){
        return $this->testMode;
    }

    /**
     * @return string
     */
    protected function getClientEndpoint()
    {
        return $this->getTestMode() ? $this->testClientEndpoint : $this->liveClientEndpoint;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        if(isset($this->data['error'])){
            return $this->data['error'];
        }

        if(isset($this->data['warning'])){
            return $this->data['warning'];
        }

        if(isset($this->data['RESULT_CODE'])){
            return ResultCodeMessages::getDescription($this->data['RESULT_CODE']);
        }

        return null;
    }

}