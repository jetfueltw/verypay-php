<?php

namespace Test;

use Faker\Factory;

use Jetfuel\Verypay\Constants\Channel;
use Jetfuel\Verypay\DigitalPayment;
use Jetfuel\Verypay\TradeQuery;
use Jetfuel\Verypay\Traits\NotifyWebhook;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    private $merchantId;
    private $secretKey;
    private $merchantPrivateKey;
    private $gatewayPublicKey;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->merchantId = getenv('MERCHANT_ID');
        $this->secretKey = getenv('SECRET_KEY');
        $this->merchantPrivateKey = getenv('MERCHANT_PRIVATE_KEY');
        $this->gatewayPublicKey = getenv('GATEWAY_PUBLIC_KEY');
    }

    public function testDigitalPaymentOrder()
    {
        $faker = Factory::create();
        $tradeNo = date('YmdHis').rand(1000, 9999);
        $channel = Channel::WECHAT;
        $amount = 1;
        $notifyUrl = $faker->url;
        $returnUrl = $faker->url;

        $payment = new DigitalPayment($this->merchantId, $this->secretKey, $this->merchantPrivateKey, $this->gatewayPublicKey, 'http://139.199.195.194:8080/');
        $result = $payment->order($tradeNo, $channel, $amount, $notifyUrl, $returnUrl);

        $this->assertEquals('00', $result['stateCode']);

        return $tradeNo;
    }

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    public function testDigitalPaymentOrderFind($tradeNo)
    {
        $channel = Channel::WECHAT;
        $amount = 1;
        $payDate = date('Y-m-d');

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey, $this->merchantPrivateKey, $this->gatewayPublicKey, 'http://139.199.195.194:8080/');
        $result = $tradeQuery->find($tradeNo, $channel, $amount, $payDate);

        $this->assertEquals('00', $result['stateCode']);
    }

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    public function testDigitalPaymentOrderIsPaid($tradeNo)
    {
        $channel = Channel::WECHAT;
        $amount = 1;
        $payDate = date('Y-m-d');

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey, $this->merchantPrivateKey, $this->gatewayPublicKey, 'http://139.199.195.194:8080/');
        $result = $tradeQuery->isPaid($tradeNo, $channel, $amount, $payDate);

        $this->assertFalse($result);
    }

    public function testNotifyWebhookVerifyNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'data'     => 'VwbywBPocHXUAKOSly8w%2BvqGRNHg%2FfioIvUTj644ta1wQ6qKjxBSSMPKGHIN3wJYst4bJrQygoAj%0D%0AF88V8hllQUCCh28uHs7GvUp4cezBCNoVDkiNQ9DN2xvuam4lYlp1xXeuyAPDWtPHg3Q7qtxFivNC%0D%0AJDBA9vIc2pq1P997MjqCcoFi4uILZWJZdDJwfIZnYeHo%2F84KMPuVjmNkKQ7eIXXmMvp03OAzW%2BJN%0D%0AyH%2BAtjxBaPueTrFQgQeirdiplaWbYBtez4gdACmC25b6MkaoPdx671%2FnkUPvqOKQWy5b74EZPDCw%0D%0ALCEis4jZ3%2BgU5jSjGnrk%2BVFPJ4DJwFitserT%2Bw%3D%3D',
            'merchNo'  => 'qyf201705200001',
            'orderNum' => '20170812104118797WlN',
        ];

        $this->assertTrue($mock->verifyNotifyPayload($payload, $this->secretKey, $this->merchantPrivateKey, true));
    }

    public function testNotifyWebhookParseNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'data'     => 'VwbywBPocHXUAKOSly8w%2BvqGRNHg%2FfioIvUTj644ta1wQ6qKjxBSSMPKGHIN3wJYst4bJrQygoAj%0D%0AF88V8hllQUCCh28uHs7GvUp4cezBCNoVDkiNQ9DN2xvuam4lYlp1xXeuyAPDWtPHg3Q7qtxFivNC%0D%0AJDBA9vIc2pq1P997MjqCcoFi4uILZWJZdDJwfIZnYeHo%2F84KMPuVjmNkKQ7eIXXmMvp03OAzW%2BJN%0D%0AyH%2BAtjxBaPueTrFQgQeirdiplaWbYBtez4gdACmC25b6MkaoPdx671%2FnkUPvqOKQWy5b74EZPDCw%0D%0ALCEis4jZ3%2BgU5jSjGnrk%2BVFPJ4DJwFitserT%2Bw%3D%3D',
            'merchNo'  => 'qyf201705200001',
            'orderNum' => '20170812104118797WlN',
        ];

        $this->assertEquals('00', $mock->parseNotifyPayload($payload, $this->secretKey, $this->merchantPrivateKey, true)['payResult']);
    }

    public function testNotifyWebhookSuccessNotifyResponse()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $this->assertEquals('0', $mock->successNotifyResponse());
    }
}
