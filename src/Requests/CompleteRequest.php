<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\FirstDataLatvia\Requests;

use Omnipay\FirstDataLatvia\Responses\CompleteResponse;

/**
 * Class CompleteRequest
 * @package Omnipay\FirstDataLatvia\Requests
 */
class CompleteRequest extends AbstractRequest
{
    /**
     * @return array|mixed
     */
    public function getData(): array
    {
        return [
            'command' => 'c',
            'trans_id' => $this->getTransactionReference(),
            'client_ip_addr' => $this->getClientIp()
        ];
    }

    /**
     * @return string|null
     */
    public function getTransactionReference()
    {
        if ($this->httpRequest->getMethod() == 'POST') {
            $data = $this->httpRequest->request->all();
        } else {
            $data = $this->httpRequest->query->all();
        }

        return $data['trans_id'] ?? null;
    }

    /**
     * @param $httpResponse
     * @param array $data
     * @return CompleteResponse
     * @throws \Omnipay\FirstDataLatvia\Exceptions\UnexpectedResponse
     */
    public function createResponse($httpResponse, array $data): CompleteResponse
    {
        return new CompleteResponse($this, $data);
    }
}
