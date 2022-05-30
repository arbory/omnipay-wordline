<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\Worldline\Requests;

use Omnipay\Worldline\Responses\PurchaseResponse;

/**
 * Class PurchaseRequest
 * @package Omnipay\Worldline\Requests
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'command' => 'v',
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrencyNumeric(),
            'client_ip_addr' => $this->getClientIp(),
            'description' => $this->getDescription(),
            'language' => $this->getLanguage(),
        ];
    }

    /**
     * @param $httpResponse
     * @param array $data
     * @return PurchaseResponse
     * @throws \Omnipay\Worldline\Exceptions\UnexpectedResponse
     */
    public function createResponse($httpResponse, array $data): PurchaseResponse
    {
        return new PurchaseResponse($this, $data);
    }
}
