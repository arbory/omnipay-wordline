<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\FirstDataLatvia\Requests;

use Omnipay\FirstDataLatvia\Responses\CaptureResponse;

/**
 * Class CaptureRequest
 * @package Omnipay\FirstDataLatvia\Requests
 */
class CaptureRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData(): array
    {
        $data = [
            'command'           => 't',
            'msg_type'          => 'DMS',
            'trans_id'          => $this->getTransactionReference(),
            'amount'            => $this->getAmountInteger(),
            'currency'          => $this->getCurrencyNumeric(),
            'client_ip_addr'    => $this->getClientIp()
        ];

        return $data;
    }

    /**
     * @param $httpResponse
     * @param array $data
     * @return CaptureResponse
     * @throws \Omnipay\FirstDataLatvia\Exceptions\UnexpectedResponse
     */
    public function createResponse($httpResponse, array $data): CaptureResponse
    {
        return new CaptureResponse($this, $data);
    }
}
