<?php

namespace Omnipay\FirstDataLatvia;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\Common\Http\ClientInterface;
use Http\Adapter\Guzzle6\Client;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var \Omnipay\FirstDataLatvia\Gateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $options;

    public function setUp(): void
    {
        parent::setUp();

        // fixture keystore generated with:
        // 1. `openssl req -x509 -newkey rsa:2048 -keyout tests/Fixtures/key.pem -out tests/Fixtures/cert.pem  -subj "/CN=test" -days 3650 -passout pass:XXXX`
        // 2. `openssl pkcs12 -export -in tests/Fixtures/cert.pem -out tests/Fixtures/keystore.p12 -certfile tests/Fixtures/letsencrypt_ca.pem -inkey tests/Fixtures/key.pem -passin pass:XXXX  -passout pass:XXXX`
        // 3. `openssl pkcs12 -in tests/Fixtures/keystore.p12 > tests/Fixtures/keystore.pem -passin pass:XXXX -passout pass:XXXX`

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setCertificatePath('tests/Fixtures/keystore.pem');
        $this->gateway->setCertificatePassword('XXXX');
        $this->gateway->setTestMode(true);

        $this->options = array(
            'transactionReference' => 'abc123',
            'description' => 'purchase description',
            'amount' => '10.00',
            'clientIP' => '127.0.0.1',
            'currency' => 'EUR',
        );
    }

    public function initializeCustomGateway(ClientInterface $httpClient = null)
    {
        return new class($httpClient) extends Gateway
        {
            public function getHttpClient()
            {
                return $this->httpClient;
            }

            public function getDefaultHttpClient()
            {
                throw new \Exception("getDefaultHttpClient method called");
            }

            public function createRequest($class, array $parameters)
            {
                return parent::createRequest($class, $parameters);
            }
        };
    }

    public function testConstructWithHttpClientPassed()
    {
        $gateway = $this->initializeCustomGateway($this->getHttpClient());

        $this->assertEquals($this->getHttpClient(), $gateway->getHttpClient());
    }

    public function testConstructWithouthHttpClientPassed()
    {
        $gateway = $this->initializeCustomGateway(null);
        $this->assertNull($gateway->getHttpClient());
    }

    public function testCreateRequestWithtHttpClientAssigned()
    {
        $gateway = $this->initializeCustomGateway($this->getHttpClient());
        $request = $gateway->createRequest(\Omnipay\FirstDataLatvia\Requests\CompleteRequest::class, array());
        $this->assertNotNull($request);
    }

    public function testCreateRequestWithouttHttpClientAssigned()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('getDefaultHttpClient method called');

        $gateway = $this->initializeCustomGateway(null);
        $request = $gateway->createRequest(\Omnipay\FirstDataLatvia\Requests\CompleteRequest::class, array());
        $this->assertNull($request);
    }

    public function testGetDefaultHttpClient()
    {
        $httpClient = $this->gateway->getDefaultHttpClient();
        $publicClientCertificateTestUrl = 'https://server.cryptomix.com/secure/';

        // test against public client certificate validation service (it accepts any client sertificate and returns this
        // certificate information)
        // altought on some environments this test will fail as there is unidentified error with some openssl/curl? versions.
        // tt's reporting "unable to get local issuer certificate" when using self signed test client certificate.
        // same does not happens with curl command line client:
        // `curl -v --cert tests/Fixtures/keystore.pem:XXXX --cacert tests/Fixtures/keystore.pem  https://server.cryptomix.com/secure/`
        $httpResponse = $httpClient->request('GET', $publicClientCertificateTestUrl);

        $this->assertStringContainsString('[SSL_CLIENT_S_DN_CN] => test', $httpResponse->getBody()->getContents());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('purchaseSuccess.txt');

        // send request to firstdata
        $response = $this->gateway->purchase($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'v',
            'amount' => '1000',
            'currency' => '978',
            'client_ip_addr' => '127.0.0.1',
            'description' => 'purchase description',
            'language' => 'EN',
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertFalse($response->isTransparentRedirect());
        $this->assertEquals(array(), $response->getRedirectData());
        $this->assertEquals('0AmRNR/ntNUZpeTkHSCGVw1wivc=', $response->getTransactionReference());
        $this->assertEquals('GET', $response->getRedirectMethod());
        $this->assertEquals('https://securepaymentpage-test.baltic.worldline-solutions.com/ecomm/ClientHandler?trans_id=0AmRNR%2FntNUZpeTkHSCGVw1wivc%3D', $response->getRedirectUrl());
    }

    public function testPurchaseFailed()
    {
        $this->setMockHttpResponse('purchaseFailed.txt');

        // expect exception as this cannot be user related error
        $this->expectException(\Omnipay\FirstDataLatvia\Exceptions\UnexpectedResponse::class);
        $this->expectExceptionMessage('parameter \'command\' not specified');

        // send request to firstdata
        $response = $this->gateway->purchase($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'amount' => '1000',
            'currency' => '978',
            'client_ip_addr' => '127.0.0.1',
            'description' => 'purchase description',
            'language' => 'EN',
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);
    }

    public function testCompletePurchaseSuccess()
    {
        $this->setMockHttpResponse('completePurchaseSuccess.txt');

        // simulate remote request to us
        $clientPostData = array(
            'trans_id' => '12345678'
        );
        $this->getHttpRequest()->setMethod('POST');
        $this->getHttpRequest()->request->replace($clientPostData);

        // send request to firstdata
        $response = $this->gateway->completePurchase($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'c',
            'trans_id' => '12345678',
            'client_ip_addr' => '127.0.0.1'
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertSame('Approved', $response->getMessage());
        $this->assertSame('12345678', $response->getTransactionReference());
    }

    public function testCompletePurchaseSuccessWithGET()
    {
        $this->setMockHttpResponse('completePurchaseSuccess.txt');

        // simulate remote request to us
        $clientGetData = array(
            'trans_id' => '12345678'
        );
        $this->getHttpRequest()->query->replace($clientGetData);

        // send request to firstdata
        $response = $this->gateway->completePurchase($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'c',
            'trans_id' => '12345678',
            'client_ip_addr' => '127.0.0.1'
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertSame('Approved', $response->getMessage());
        $this->assertSame('12345678', $response->getTransactionReference());
    }

    public function testCompletePurchaseFailed()
    {
        $this->setMockHttpResponse('completePurchaseFailed.txt');

        // simulate remote request to us
        $clientPostData = array(
            'trans_id' => '12345678'
        );
        $this->getHttpRequest()->setMethod('POST');
        $this->getHttpRequest()->request->replace($clientPostData);

        // send request to firstdata
        $response = $this->gateway->completePurchase($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'c',
            'trans_id' => '12345678',
            'client_ip_addr' => '127.0.0.1'
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Decline (general, no comments)', $response->getMessage());
        $this->assertSame('12345678', $response->getTransactionReference());
    }

    public function testAuthorize()
    {
        $this->setMockHttpResponse('authorizeSuccess.txt');

        // send request to firstdata
        $response = $this->gateway->authorize($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'a',
            'msg_type' => 'DMS',
            'amount' => '1000',
            'currency' => '978',
            'client_ip_addr' => '127.0.0.1',
            'description' => 'purchase description',
            'language' => 'EN',
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertFalse($response->isTransparentRedirect());
        $this->assertEquals(array(), $response->getRedirectData());
        $this->assertEquals('BBmRNR/ntNUZpeTkHSCGVw1wivc=', $response->getTransactionReference());
        $this->assertEquals('GET', $response->getRedirectMethod());
        $this->assertEquals('https://securepaymentpage-test.baltic.worldline-solutions.com/ecomm/ClientHandler?trans_id=BBmRNR%2FntNUZpeTkHSCGVw1wivc%3D', $response->getRedirectUrl());
    }

    public function testAuthorizeFailed()
    {
        $this->setMockHttpResponse('authorizeFailed.txt');

        // expect exception as this cannot be user related error
        $this->expectException(\Omnipay\FirstDataLatvia\Exceptions\UnexpectedResponse::class);
        $this->expectExceptionMessage('parameter \'command\' not specified');

        // send request to firstdata
        $response = $this->gateway->authorize($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'msg_type' => 'DMS',
            'amount' => '1000',
            'currency' => '978',
            'client_ip_addr' => '127.0.0.1',
            'description' => 'purchase description',
            'language' => 'EN',
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);
    }

    public function testCompleteAuthorizeSuccess()
    {
        $this->setMockHttpResponse('completeAuthorizeSuccess.txt');

        // simulate remote request to us
        $clientPostData = array(
            'trans_id' => '12345678'
        );
        $this->getHttpRequest()->setMethod('POST');
        $this->getHttpRequest()->request->replace($clientPostData);

        // send request to firstdata
        $response = $this->gateway->completeAuthorize($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'c',
            'trans_id' => '12345678',
            'client_ip_addr' => '127.0.0.1'
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testCompleteAuthorizeSuccessWithGET()
    {
        $this->setMockHttpResponse('completeAuthorizeSuccess.txt');

        // simulate remote request to us
        $clientGetData = array(
            'trans_id' => '12345678'
        );
        $this->getHttpRequest()->query->replace($clientGetData);

        // send request to firstdata
        $response = $this->gateway->completeAuthorize($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'c',
            'trans_id' => '12345678',
            'client_ip_addr' => '127.0.0.1'
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testCompleteAuthorizeFailed()
    {
        $this->setMockHttpResponse('completeAuthorizeFailed.txt');

        // simulate remote request to us
        $clientPostData = array(
            'trans_id' => '12345678'
        );
        $this->getHttpRequest()->setMethod('POST');
        $this->getHttpRequest()->request->replace($clientPostData);

        // send request to firstdata
        $response = $this->gateway->completeAuthorize($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'c',
            'trans_id' => '12345678',
            'client_ip_addr' => '127.0.0.1'
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Decline (general, no comments)', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $this->setMockHttpResponse('captureSuccess.txt');

        // send request to firstdata
        $response = $this->gateway->capture($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 't',
            'msg_type' => 'DMS',
            'trans_id' => 'abc123',
            'amount' => '1000',
            'currency' => '978',
            'client_ip_addr' => '127.0.0.1'
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testCaptureFailed()
    {
        $this->setMockHttpResponse('captureFailed.txt');

        // send request to firstdata
        $response = $this->gateway->capture($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 't',
            'msg_type' => 'DMS',
            'trans_id' => 'abc123',
            'amount' => '1000',
            'currency' => '978',
            'client_ip_addr' => '127.0.0.1'
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Decline (general, no comments)', $response->getMessage());
    }

    public function testVoidSuccess()
    {
        $this->setMockHttpResponse('voidSuccess.txt');

        // send request to firstdata
        $response = $this->gateway->void($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'r',
            'amount' => '1000',
            'trans_id' => 'abc123',
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Accepted (for reversal)', $response->getMessage());
    }

    public function testVoidFailed()
    {
        $this->setMockHttpResponse('voidFailed.txt');

        // send request to firstdata
        $response = $this->gateway->void($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'r',
            'amount' => '1000',
            'trans_id' => 'abc123',
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Decline reason message: invalid transaction', $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('refundSuccess.txt');

        // send request to firstdata
        $response = $this->gateway->refund($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'k',
            'amount' => '1000',
            'trans_id' => 'abc123',
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('xxxx', $response->getRefundTransaction());
        $this->assertSame('Accepted (for reversal)', $response->getMessage());
    }

    public function testRefundFailed()
    {
        $this->setMockHttpResponse('refundFailed.txt');

        // send request to firstdata
        $response = $this->gateway->refund($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'k',
            'amount' => '1000',
            'trans_id' => 'abc123',
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Decline reason message: invalid transaction', $response->getMessage());
    }

    public function testCloseDaySuccess()
    {
        $this->setMockHttpResponse('closeDaySuccess.txt');

        // send request to firstdata
        $response = $this->gateway->closeDay($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'b',
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Status message: reconciled, in balance', $response->getMessage());
    }

    public function testCloseDayFailed()
    {
        $this->setMockHttpResponse('closeDayFailed.txt');

        // send request to firstdata
        $response = $this->gateway->closeDay($this->options)->send();

        // test actual data we are sending to firstdata
        $sentPostData = array(
            'command' => 'b',
        );
        $httpRequests = $this->getMockedRequests();
        $httpRequest = $httpRequests[0];
        parse_str((string)$httpRequest->getBody(), $postData);
        $this->assertSame($postData, $sentPostData);

        // test firstdata response
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Decline (general, no comments)', $response->getMessage());
    }
}
