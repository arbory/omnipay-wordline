<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\Worldline\Requests;

use Omnipay\Worldline\Responses\CloseDayResponse;

/**
 * Class CloseDayRequest
 * @package Omnipay\Worldline\Requests
 */
class CloseDayRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'command' => 'b'
        ];
    }

    /**
     * @param $httpResponse
     * @param array $data
     * @return CloseDayResponse
     * @throws \Exception
     */
    public function createResponse($httpResponse, array $data): CloseDayResponse
    {
        return new CloseDayResponse($this, $data);
    }
}
