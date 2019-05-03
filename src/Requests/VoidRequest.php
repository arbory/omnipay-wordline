<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\FirstDataLatvia\Requests;

use Omnipay\FirstDataLatvia\Responses\VoidResponse;

/**
 * Class VoidRequest
 * @package Omnipay\FirstDataLatvia\Requests
 */
class VoidRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'command' => 'r',
            'amount' => $this->getAmountInteger(),
            'trans_id' => $this->getTransactionReference()
        ];
    }

    /**
     * @param $httpResponse
     * @param array $data
     * @return VoidResponse
     */
    public function createResponse($httpResponse, array $data): VoidResponse
    {
        return new VoidResponse($this, $data);
    }
}
