<?php

/**
 * @see https://wiki.php.net/rfc/scalar_type_hints_v5
 */
declare(strict_types=1);

namespace Omnipay\Worldline\Responses;

use Omnipay\Worldline\Helpers\ResultCodeMessageHelper;
use Omnipay\Worldline\Exceptions\UnexpectedResponse;
use Omnipay\Common\Message\AbstractResponse as CommonAbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class AbstractResponse
 * @package Omnipay\Worldline\Responses
 */
abstract class AbstractResponse extends CommonAbstractResponse
{
    protected const RESULT_OK = 'OK';

    protected const RESULT_TIMEOUT = 'TIMEOUT';

    protected const RESULT_KEY = 'RESULT';
    protected const RESULT_CODE_KEY = 'RESULT_CODE';
    protected const RESULT_SUCCESS_CODE = '000';

    /**
     * @var string
     */
    protected $testClientEndpoint = 'https://securepaymentpage-test.baltic.worldline-solutions.com/ecomm/ClientHandler';

    /**
     * @var string
     */
    protected $liveClientEndpoint = 'https://securepaymentpage.baltic.worldline-solutions.com/ecomm/ClientHandler';

    /**
     * @var bool
     */
    protected $testMode = false;

    /**
     * AbstractResponse constructor.
     * @param RequestInterface $request
     * @param array $data
     * @throws UnexpectedResponse
     */
    public function __construct(RequestInterface $request, array $data)
    {
        parent::__construct($request, $data);

        // usualy this happen when there is non-user related error (wrong request Content-Type etc.)
        if (isset($this->data['error']) && sizeof($this->data) == 1) {
            throw new UnexpectedResponse($this->data['error']);
        }
    }

    /**
     * @param bool $value
     */
    public function setTestMode(bool $value): void
    {
        $this->testMode = $value;
    }

    /**
     * @return bool
     */
    public function getTestMode(): bool
    {
        return $this->testMode;
    }

    /**
     * @return string
     */
    protected function getClientEndpoint(): string
    {
        return $this->getTestMode() ? $this->testClientEndpoint : $this->liveClientEndpoint;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        $code = ($this->data[self::RESULT_CODE_KEY] ?? null);

        if ($code !== null) {
            return ResultCodeMessageHelper::generateDescription((int)$code);
        }

        return null;
    }

    /**
     * Use only RESULT data to determine transactions state
     * Other fields are for debugging and logging!
     * This is from Payeezy IP admin manual
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return ($this->data[self::RESULT_KEY] ?? null) === self::RESULT_OK;
    }

    /**
     * Checks if user has canceled transaction
     * Only way user can cancel transaction is via timeout, there are no other ways
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return ($this->data[self::RESULT_KEY] ?? null) === self::RESULT_TIMEOUT;
    }
}
