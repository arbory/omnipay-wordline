<?php

namespace Omnipay\FirstDataLatvia\Messages;

use  Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;

abstract class AbstractRequest extends CommonAbstractRequest
{

    /**
     * @var string
     */
    protected $testServerEndpoint = 'https://secureshop-test.firstdata.lv:8443/ecomm/MerchantHandler';

    /**
     * @var string
     */
    protected $liveServerEndpoint = 'https://secureshop.firstdata.lv:8443/ecomm/MerchantHandler';

    /**
     * @param $data array
     * @param $httpClient
     * @return AbstractResponse
     */
    abstract protected function createResponse($httpResponse, array $data);

    /**
     * @param string $value
     * @return $this
     */
    public function setCertificatePassword($value)
    {
        return $this->setParameter('certificatePassword', $value);
    }

    /**
     * @return string
     */
    public function getCertificatePassword()
    {
        return $this->getParameter('certificatePassword');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCertificatePath($value)
    {
        return $this->setParameter('certificatePath', $value);
    }

    /**
     * @return string
     */
    public function getCertificatePath()
    {
        return $this->getParameter('certificatePath');
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->getParameter('command');
    }

    /**
     * @param $value
     * @return CommonAbstractRequest
     */
    public function setCommand($value)
    {
        return $this->setParameter('command', $value);
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * @param $value
     * @return CommonAbstractRequest
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * @return string
     */
    protected function getServerEndpoint()
    {
        return $this->getTestMode() ? $this->testServerEndpoint : $this->liveServerEndpoint;
    }


    /**
     * @param mixed $data
     * @return \Guzzle\Http\Message\Response
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function sendData($data)
    {
        $this->validate('certificatePath', 'certificatePassword');

        $endpointUrl = $this->getServerEndpoint();
        $requestBody = $data;
        $options = [];
        $requestHeaders = [];

        //http://guzzle3.readthedocs.io/docs.html
        $client = $this->httpClient->post(
            $endpointUrl,
            $requestHeaders,
            $requestBody,
            $options
        );

        $sslPath = $this->getParameter('certificatePath');
        $sslPassword = $this->getParameter('certificatePassword');

        $client->getCurlOptions()->set(CURLOPT_URL, $endpointUrl);
        $client->getCurlOptions()->set(CURLOPT_HEADER, 0);
        $client->getCurlOptions()->set(CURLOPT_POST, true);
        $client->getCurlOptions()->set(CURLOPT_SSL_VERIFYPEER, false);
        $client->getCurlOptions()->set(CURLOPT_SSLCERT, $sslPath);
        $client->getCurlOptions()->set(CURLOPT_CAINFO, $sslPath);
        $client->getCurlOptions()->set(CURLOPT_SSLKEYPASSWD, $sslPassword);
        $client->getCurlOptions()->set(CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($curl, CURLOPT_SSLVERSION,2);

        $httpResponse = $client->send();
        /** @var AbstractResponse $purchaseResponseObj */
        $purchaseResponseObj = $this->createResponse($httpResponse, $this->getResponseArray($httpResponse->getBody()));
        $purchaseResponseObj->setTestMode($this->getTestMode());

        return $purchaseResponseObj;
    }

    /**
     * @param $response
     * @return array
     */
    protected static function getResponseArray($response)
    {
        $data = [];
        $tmp = explode("\n", $response);
        foreach ($tmp as $tmpData) {
            $tmpData = explode(': ', $tmpData);
            $tmpData2 = $tmpData;
            unset($tmpData2[0]);
            $data[$tmpData[0]] = implode(': ', $tmpData2);
        }
        return $data;
    }
}