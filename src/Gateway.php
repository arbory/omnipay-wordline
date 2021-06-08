<?php

namespace Omnipay\FirstDataLatvia;

use Omnipay\Common\AbstractGateway;
use Omnipay\FirstDataLatvia\Messages\AuthorizeRecurringRequest;
use Omnipay\FirstDataLatvia\Messages\CaptureRequest;
use Omnipay\FirstDataLatvia\Messages\CloseDayRequest;
use Omnipay\FirstDataLatvia\Messages\CompleteRequest;
use Omnipay\FirstDataLatvia\Messages\OverwriteRecurringWithoutPayment;
use Omnipay\FirstDataLatvia\Messages\PurchaseRequest;
use Omnipay\FirstDataLatvia\Messages\AuthorizeRequest;
use Omnipay\FirstDataLatvia\Messages\RefundRequest;
use Omnipay\FirstDataLatvia\Messages\ReversalRequest;
use Omnipay\FirstDataLatvia\Messages\VoidRequest;
use Omnipay\FirstDataLatvia\Messages\ExecuteRecurringRequest;

/**
 * Class Gateway
 *
 * @package Omnipay\FirstDataLatvia
 */
class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'First Data Latvia';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'certificatePassword' => '',
            'certificatePath' => '',
            'testMode' => false,
//            'clientIP' => $_SERVER['REMOTE_ADDR'] ?? $_SERVER['REMOTE_ADDR']
        );
    }

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
    public function getClientIP()
    {
        return $this->getParameter('clientIP');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setClientIP($value)
    {
        return $this->setParameter('clientIP', $value);
    }

    /**
     * Execute SMS transaction
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $options = [])
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    /**
     * Request transaction result
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function completePurchase(array $options = [])
    {
        return $this->createRequest(CompleteRequest::class, $options);
    }

    /**
     * Execute DMS1 transaction
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function authorize(array $options = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    /**
     * Authorize for recurring payments
     *
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function authorizeRecurring(array $options = [])
    {
        return $this->createRequest(AuthorizeRecurringRequest::class, $options);
    }

    /**
     * Execute recurring payment
     *
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function executeRecurring(array $options = [])
    {
         return $this->createRequest(ExecuteRecurringRequest::class, $options);
    }

    /**
     * Overwrite an existing recurring payment with new card data
     *
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function overwriteRecurringWithoutPayment(array $options = [])
    {
        return $this->createRequest(OverwriteRecurringWithoutPayment::class, $options);
    }

    /**
     * Request transaction result
     * In case of DMS1 transaction you also should request transaction result within 3 minutes. But if after
     * DMS1 within 3 minutes you perform DMS2 transaction, you don’t need to request additionally DMS1
     * transaction result. For DMS2 transaction result is returned automatically.
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function completeAuthorize(array $options = [])
    {
        return $this->createRequest(CompleteRequest::class, $options);
    }

    /**
     * Execute DMS2 transaction
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function capture(array $options = [])
    {
        return $this->createRequest(CaptureRequest::class, $options);
    }

    /**
     * Transaction reversals are used to negate or cancel a Transaction when there has been a technical error. Technical error!
     *
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function void(array $options = [])
    {
        return $this->createRequest(VoidRequest::class, $options);
    }

    /**
     * According to scheme rules Refunds are used for customer service or legal reasons: to credit a
     * Cardholder’s account for returned products or cancelled services, or for price adjustments, related
     * to a prior purchase. MasterCard explicitly states, that refunds can be used only for these purposes.
     *
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function refund(array $options = [])
    {
        return $this->createRequest(RefundRequest::class, $options);
    }

    /**
     * Transaction reversals are used to negate or cancel a Transaction when there has been a technical error.
     *
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function reverse(array $options = [])
    {
        return $this->createRequest(ReversalRequest::class, $options);
    }


    /**
     * Closes business day
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function closeDay(array $options = [])
    {
        return $this->createRequest(CloseDayRequest::class, $options);
    }
}