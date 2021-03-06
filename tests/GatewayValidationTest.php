<?php

namespace Omnipay\Heartland\Message;

use Omnipay\Tests\TestCase;

class GatewayValidationTest extends TestCase
{
    public function setUp()
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '12.00',
                'currency' => 'USD',
                'card' => $this->getValidCard(),
                'description' => 'Order #42'
            )
        );
        $this->request->setSecretApiKey('skapi_cert_MYl2AQAowiQAbLp5JesGKh7QFkcizOP2jcX9BrEMqQ');
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The siteId parameter is required
     */
    public function testSiteIdRequired()
    {
        $this->request->setSecretApiKey(null);
        $this->request->validateConnectionParameters();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The deviceId parameter is required
     */
    public function testDeviceIdRequired()
    {
        $this->request->setSecretApiKey(null);
        $this->request->setSiteId('20518');
        $this->request->validateConnectionParameters();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The licenseId parameter is required
     */
    public function testLicenseIdRequired()
    {
        $this->request->setSecretApiKey(null);
        $this->request->setSiteId('20518');
        $this->request->setDeviceId('90911395');
        $this->request->validateConnectionParameters();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The username parameter is required
     */
    public function testUsernameRequired()
    {
        $this->request->setSecretApiKey(null);
        $this->request->setSiteId('20518');
        $this->request->setDeviceId('90911395');
        $this->request->setLicenseId('20527');
        $this->request->validateConnectionParameters();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The password parameter is required
     */
    public function testPasswordRequired()
    {
        $this->request->setSecretApiKey(null);
        $this->request->setSiteId('20518');
        $this->request->setDeviceId('90911395');
        $this->request->setLicenseId('20527');
        $this->request->setUsername('30360021');
        $this->request->validateConnectionParameters();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The serviceUri parameter is required
     */
    public function testServiceUriRequired()
    {
        $this->request->setSecretApiKey(null);
        $this->request->setSiteId('20518');
        $this->request->setDeviceId('90911395');
        $this->request->setLicenseId('20527');
        $this->request->setUsername('30360021');
        $this->request->setPassword('$Test1234');
        $this->request->validateConnectionParameters();
    }

    public function testDOMElementCreated()
    {
        $this->request->setSecretApiKey(null);
        $this->request->setSiteId('20518');
        $this->request->setDeviceId('90911395');
        $this->request->setLicenseId('20527');
        $this->request->setUsername('30360021');
        $this->request->setPassword('$Test1234');
        $this->request->setServiceUri("https://api-uat.heartlandportico.com/paymentserver.v1/PosGatewayService.asmx");
        $data = $this->request->getData();
        $this->assertInstanceOf('DOMElement', $data);
    }

    public function testSecretApikey()
    {
        $data = $this->request->getData();
        $this->assertSame('skapi_cert_MYl2AQAowiQAbLp5JesGKh7QFkcizOP2jcX9BrEMqQ', $this->request->getSecretApiKey());
        $this->assertInstanceOf('DOMElement', $data);
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The card parameter is required
     */
    public function testCardRequired()
    {
        $this->request->setCard(null);
        $this->request->getData();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The card parameter is required
     */
    public function testTokenNull()
    {
        $this->request->setCard(null);
        $this->request->setToken(null);
        $this->request->getData();
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The card parameter is required
     */
    public function testCardReferenceNull()
    {
        $this->request->setCard(null);
        $this->request->setToken(null);
        $this->request->setCardReference(null);
        $this->request->getData();
    }

    public function testDataWithCard()
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);
        $data = $this->request->getData();

        $this->assertInstanceOf('DOMElement', $data);
    }


    public function testDataWithToken()
    {
        $this->request->setToken('supt_ca67zN30E7YEE1etcQabwo4g');
        $data = $this->request->getData();

        $this->assertSame('supt_ca67zN30E7YEE1etcQabwo4g', $this->request->getToken());
        $this->assertInstanceOf('DOMElement', $data);
    }

    public function testDataWithCardReference()
    {
        $this->request->setCardReference('supt_ca67zN30E7YEE1etcQabwo4g');
        $data = $this->request->getData();

        $this->assertSame('supt_ca67zN30E7YEE1etcQabwo4g', $this->request->getToken());
        $this->assertSame('supt_ca67zN30E7YEE1etcQabwo4g', $this->request->getCardReference());
        $this->assertInstanceOf('DOMElement', $data);
    }

}
