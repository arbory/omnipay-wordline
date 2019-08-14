<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\FirstDataLatvia;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Http\ClientInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Http\Adapter\Guzzle6\Client;
use Omnipay\FirstDataLatvia\Requests\AuthorizeRequest;
use Omnipay\FirstDataLatvia\Requests\CaptureRequest;
use Omnipay\FirstDataLatvia\Requests\CloseDayRequest;
use Omnipay\FirstDataLatvia\Requests\CompleteRequest;
use Omnipay\FirstDataLatvia\Requests\PurchaseRequest;
use Omnipay\FirstDataLatvia\Requests\RefundRequest;
use Omnipay\FirstDataLatvia\Requests\VoidRequest;
use Illuminate\Http\Request;

/**
 * Class Gateway
 *
 * @package Omnipay\FirstDataLatvia
 */
class Gateway extends AbstractGateway
{
    public const TRANSACTION_ID_KEY = 'TRANSACTION_ID';

    public function __construct(ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest ?: $this->getDefaultHttpRequest();
        $this->initialize();
    }

    /**
     * Create and initialize a request object
     *
     * @param string $class The request class name
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    protected function createRequest($class, array $parameters)
    {
        $this->httpClient = $this->httpClient ?: $this->getDefaultHttpClient();
        return parent::createRequest($class, $parameters);
    }

    /**
     * @return array
     */
    protected function getHttpClientConfig(): array
    {
        return [
            'allow_redirects' => false,
            'cookies' => true,
            'verify' => $this->getParameter('certificatePath'), // this will make use of included CA certificate
            'cert' => array($this->getParameter('certificatePath'), $this->getParameter('certificatePassword'))
        ];
    }

    /**
     * Get the global default HTTP client.
     *
     * @return ClientInterface
     */
    public function getDefaultHttpClient()
    {
        $guzzleClient = Client::createWithConfig($this->getHttpClientConfig());

        return new \Omnipay\Common\Http\Client($guzzleClient);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'First Data Latvia';
    }

    /**
     * @return array
     */
    public function getDefaultParameters(): array
    {
        return [
            'testMode' => false,
            'language' => 'EN'
        ];
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setCertificatePassword($value): self
    {
        return $this->setParameter('certificatePassword', $value);
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
    public function getLanguage(): string
    {
        return $this->getParameter('language');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * Execute SMS transaction
     * @param array $options
     * @return PurchaseRequest|AbstractRequest
     */
    public function purchase(array $options = []): PurchaseRequest
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    /**
     * Request transaction result
     * @param array $options
     * @return AbstractRequest|CompleteRequest
     */
    public function completePurchase(array $options = []): CompleteRequest
    {
        return $this->createRequest(CompleteRequest::class, $options);
    }

    /**
     * Execute DMS1 transaction
     * @param array $options
     * @return AbstractRequest|AuthorizeRequest
     */
    public function authorize(array $options = []): AuthorizeRequest
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    /**
     * Request transaction result
     * In case of DMS1 transaction you also should request transaction result within 3 minutes. But if after
     * DMS1 within 3 minutes you perform DMS2 transaction, you don’t need to request additionally DMS1
     * transaction result. For DMS2 transaction result is returned automatically.
     * @param array $options
     * @return AbstractRequest|CompleteRequest
     */
    public function completeAuthorize(array $options = []): CompleteRequest
    {
        return $this->createRequest(CompleteRequest::class, $options);
    }

    /**
     * Execute DMS2 transaction
     * @param array $options
     * @return AbstractRequest|CaptureRequest
     */
    public function capture(array $options = []): CaptureRequest
    {
        return $this->createRequest(CaptureRequest::class, $options);
    }

    /**
     * Transaction reversals are used to negate or cancel a Transaction when there has been a technical error.
     *
     * @param array $options
     * @return AbstractRequest|VoidRequest
     */
    public function void(array $options = []): VoidRequest
    {
        return $this->createRequest(VoidRequest::class, $options);
    }

    /**
     * According to scheme rules Refunds are used for customer service or legal reasons: to credit a
     * Cardholder’s account for returned products or cancelled services, or for price adjustments, related
     * to a prior purchase. MasterCard explicitly states, that refunds can be used only for these purposes.
     *
     * @param array $options
     * @return AbstractRequest|RefundRequest
     */
    public function refund(array $options = []): RefundRequest
    {
        return $this->createRequest(RefundRequest::class, $options);
    }


    /**
     * Closes business day
     * @param array $options
     * @return AbstractRequest|CloseDayRequest
     */
    public function closeDay(array $options = []): CloseDayRequest
    {
        return $this->createRequest(CloseDayRequest::class, $options);
    }
}
