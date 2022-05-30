<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\Worldline\Requests;

use Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;
use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Worldline\Gateway;
use Omnipay\Worldline\Responses\AbstractResponse;
use Omnipay\Common\Http\ClientInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class AbstractRequest
 * @package Omnipay\Worldline\Requests
 */
abstract class AbstractRequest extends CommonAbstractRequest
{
    /**
     * @var string
     */
    protected $testServerEndpoint = 'https://securepaymentpage-test.baltic.worldline-solutions.com:8443/ecomm/MerchantHandler'; //phpcs:ignore

    /**
     * @var string
     */
    protected $liveServerEndpoint = 'https://securepaymentpage.baltic.worldline-solutions.com:8443/ecomm/MerchantHandler'; //phpcs:ignore


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
        $this->validateCertificate();

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
     * @throws \Omnipay\Common\Exception\RuntimeException
     */
    protected function validateCertificate()
    {
        $this->validate('certificatePath', 'certificatePassword');

        # check if certificate exists
        if (!file_exists($this->getCertificatePath())) {
            throw new RuntimeException('Unexisting certificate ' . $this->getCertificatePath());
        }

        // test certificate parse with supplied password
        $pkeyid = openssl_pkey_get_private('file://' . $this->getCertificatePath(), $this->getCertificatePassword());
        if (!$pkeyid) {
            throw new RuntimeException('Unable to load certificate ' . $this->getCertificatePath());
        }
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
