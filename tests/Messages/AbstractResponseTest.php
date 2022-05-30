<?php

namespace Omnipay\Worldline;

use Omnipay\Worldline\Responses\AbstractResponse;
use Omnipay\Tests\TestCase;

class AbstractResponseTest extends TestCase
{

    public function initializeCustomResponse(array $data)
    {
        return new class($this->getMockRequest(), $data) extends AbstractResponse
        {
        };
    }

    public function testConstructWithNoErrorInData()
    {
        $data = array('RESULT' => 'asd');
        $response = $this->initializeCustomResponse($data);

        $this->assertNotNull($response);
    }

    public function testConstructWithErrorInData()
    {
        $data = array('error' => 'zxc', 'RESULT' => 'asd');
        $response = $this->initializeCustomResponse($data);

        $this->assertNotNull($response);
    }

    public function testConstructWithErrorOnlyInData()
    {
        $this->expectException(\Omnipay\Worldline\Exceptions\UnexpectedResponse::class);
        $this->expectExceptionMessage('zxc');

        $data = array('error' => 'zxc');
        $response = $this->initializeCustomResponse($data);

        $this->assertNull($response);
    }

    public function testGetMessage()
    {
        $data = array('RESULT_CODE' => '300');
        $response = $this->initializeCustomResponse($data);

        $this->assertEquals('Status message: file action successful', $response->getMessage());
    }

    public function testGetMessageWithoutResultCode()
    {
        $data = array('RESULT' => 'asd');
        $response = $this->initializeCustomResponse($data);

        $this->assertNull($response->getMessage());
    }
}
