<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\FirstDataLatvia\Requests;

use Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;
use Omnipay\FirstDataLatvia\Gateway;
use Omnipay\FirstDataLatvia\Responses\AbstractResponse;
use Omnipay\Common\Http\ClientInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class AbstractRequest
 * @package Omnipay\FirstDataLatvia\Requests
 */
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
     * @param $httpResponse
     * @return AbstractResponse
     */
    abstract protected function createResponse($httpResponse, array $data);

    /**
     * @param string $value
     * @return $this
     */
    public function setCertificatePassword($value): self
    {
        return $this->setParameter('certificatePassword', $value);
    }

    /**
     * @return string
     */
    public function getCertificatePassword(): string
    {
        return $this->getParameter('certificatePassword');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCertificatePath($value): self
    {
        return $this->setParameter('certificatePath', $value);
    }

    /**
     * @return string
     */
    public function getCertificatePath(): string
    {
        return $this->getParameter('certificatePath');
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->getParameter('language');
    }

    /**
     * @param $value
     * @return self
     */
    public function setLanguage($value): self
    {
        return $this->setParameter('language', $value);
    }

    /**
     * @return string
     */
    protected function getServerEndpoint(): string
    {
        return $this->getTestMode() ? $this->testServerEndpoint : $this->liveServerEndpoint;
    }

    /**
     * @param mixed $data
     * @return AbstractResponse
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function sendData($data): AbstractResponse
    {
        $this->validate('certificatePath', 'certificatePassword');

        $requestMethod = 'POST';
        $requestUrl = $this->getServerEndpoint();
        $requestHeaders = array(
            'Content-Type' => 'application/x-www-form-urlencoded'
        );
        $requestBody = http_build_query($data, '', '&');

        $httpResponse = $this->httpClient->request(
            $requestMethod,
            $requestUrl,
            $requestHeaders,
            $requestBody
        );

        $responseData = $this->parseResponse($httpResponse->getBody()->getContents());

        /** @var AbstractResponse $responseObj */
        $responseObj = $this->createResponse($httpResponse, $responseData);
        $responseObj->setTestMode($this->getTestMode());
        return $responseObj;
    }

    /**
     * @param $response
     * @return array
     */
    protected static function parseResponse($response): array
    {
        $data = [];
        $delimiter = ': ';
        $lines = explode("\n", trim($response));

        foreach ($lines as $line) {
            $lineSegments = explode($delimiter, $line);
            $key = array_shift($lineSegments);
            $data[$key] = implode($delimiter, $lineSegments);
        }

        return $data;
    }
}