<?php

namespace Omnipay\FirstDataLatvia;

use Omnipay\FirstDataLatvia\Requests\AbstractRequest;
use Omnipay\FirstDataLatvia\Responses\AbstractResponse;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    /**
     * @var \Omnipay\FirstDataLatvia\Requests\AbstractRequest
     */
    protected $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new class($this->getHttpClient(), $this->getHttpRequest()) extends AbstractRequest {
            public function getData(): array
            {
                return array();
            }

            public static function parseResponse($response): array
            {
                return parent::parseResponse($response);
            }

            protected function createResponse($httpResponse, array $data): AbstractResponse
            {
                return new class($this, $data) extends AbstractResponse
                {
                };
            }
        };
    }

    public function testSendDataValidateCertificatePath()
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->expectExceptionMessage('The certificatePath parameter is require');

        $this->request->sendData(array('some_data' => 'x'));
    }

    public function testSendDataValidateCertificatePassword()
    {
        $this->request->setCertificatePath("x");

        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->expectExceptionMessage('The certificatePassword parameter is require');

        $this->request->sendData(array('some_data' => 'x'));
    }

    public function testSendData()
    {
        $this->request->setCertificatePath('tests/Fixtures/keystore.pem');
        $this->request->setCertificatePassword('XXXX');
        $this->request->setTestMode(false);

        // send request to firstdata
        $response = $this->request->sendData(array('some_data' => 'x'));

        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];

        // test actual headers we are sending
        $headers = array(
            'Host' => ['secureshop.firstdata.lv:8443'],
            'Content-Type' => ['application/x-www-form-urlencoded']
        );
        $this->assertSame($headers, $httpRequest->getHeaders());

        // test actual data we are sending
        $sentPostData = array(
            'some_data' => 'x',
        );
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);
    }

    public function testSendDataMissingConfiguration()
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->expectExceptionMessage('The certificatePath parameter is required');

        $this->request->sendData(array('some_data' => 'x'));
    }

    public function testSendDatatUnexistingCertificate()
    {
        // initiate new unconfigured gateway
        $this->request->setCertificatePath('tests/Fixtures/unexisting.pem');
        $this->request->setCertificatePassword('XXXX');

        $this->expectException(\Omnipay\Common\Exception\RuntimeException::class);
        $this->expectExceptionMessage('Unexisting certificate tests/Fixtures/unexisting.pem');

        $this->request->sendData(array('some_data' => 'x'));
    }

    public function testSendDataInvalidCertificate()
    {
        $this->request->setCertificatePath('tests/Fixtures/keystore.pem');
        // set wrong password
        $this->request->setCertificatePassword('wrong-password');

        $this->expectException(\Omnipay\Common\Exception\RuntimeException::class);
        $this->expectExceptionMessage('Unable to load certificate tests/Fixtures/keystore.pem');

        $this->request->sendData(array('some_data' => 'x'));
    }

    public function testParseResponse()
    {
        $this->assertEquals(array("asd" => "1", "xccx" => "3"), $this->request::parseResponse("asd: 1\nxccx: 3\n"));
    }
}
