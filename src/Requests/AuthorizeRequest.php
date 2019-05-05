<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\FirstDataLatvia\Requests;

use Omnipay\FirstDataLatvia\Responses\AuthorizeResponse;

/**
 * Class AuthorizeRequest
 * @package Omnipay\FirstDataLatvia\Requests
 */
class AuthorizeRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData(): array
    {
        $data = [
            'command'           => 'a',
            'msg_type'          => 'DMS',
            'amount'            => $this->getAmountInteger(),
            'currency'          => $this->getCurrencyNumeric(),
            'client_ip_addr'    => $this->getClientIp(),
            'description'       => $this->getDescription(),
            'language'          => $this->getLanguage(),
        ];

        return $data;
    }

    /**
     * @param $httpResponse
     * @param array $data
     * @return PurchaseResponse
     */
    public function createResponse($httpResponse, array $data): AuthorizeResponse
    {
        return new AuthorizeResponse($this, $data);
    }
}
