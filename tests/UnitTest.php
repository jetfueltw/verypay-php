<?php

namespace Test;

use Faker\Factory;

use Jetfuel\Verypay\BankPayment;
use Jetfuel\Verypay\Constants\Bank;
use Jetfuel\Verypay\Constants\Channel;
use Jetfuel\Verypay\DigitalPayment;
use Jetfuel\Verypay\TradeQuery;
use Jetfuel\Verypay\Traits\NotifyWebhook;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    private $merchantNO;
    private $md5Key;
    private $merchantPrivateKey;
    private $merchantPayPublicKey;
    private $merchantRemitPublicKey;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->merchantNO = getenv('MERCHANT_NO');
        $this->md5Key = getenv('MD5_KEY');
        $this->merchantPrivateKey = getenv('MERCHANT_PRIVATE_KEY');
        $this->merchantPayPublicKey = getenv('MERCHANT_PAY_PUBLIC_KEY');
        $this->merchantRemitPublicKey = getenv('MERCHANT_REMIT_PUBLIC_KEY');
        
    }

    public function testSign()
    {
        $tradeNo = '20170812104118797WlN';
        $channel = Channel::ALIPAY;
        $amount = 1;
        $clientIp = '';
        $goodsName='商品名称';
        $notifyUrl = 'http://127.0.0.1/';
        $notifyView = 'http://localhost/view';
        
        $payment = new DigitalPayment($this->merchantNO, $this->md5Key, $this->merchantPrivateKey, 
                            $this->merchantPayPublicKey, $this->merchantRemitPublicKey);

        //var_dump($payment);
        
        $result = $payment->order($tradeNo, $channel, $amount, $clientIp, $goodsName, $notifyUrl, $notifyView);
        var_dump($result);
        
    }

    /*public function testDigitalPaymentOrder()
    {
        $faker = Factory::create();
        $tradeNo = str_replace('-', '', $faker->uuid);
        $channel = Channel::WECHAT;
        $amount = 1;
        $clientIp = $faker->ipv4;
        $notifyUrl = $faker->url;
        echo $tradeNo . ' ; ' . $channel . ' ; ' . $amount .' ; ' .  $clientIp . ' ; ' .$notifyUrl . '\n';

        $payment = new DigitalPayment($this->merchantId, $this->merchantPrivateKey);
        $result = $payment->order($tradeNo, $channel, $amount, $clientIp, $notifyUrl);

        echo ' result_code = ' . $result['result_code'] . '\r\n';
        echo ' error_code = ' . $result['error_code'];

        $this->assertEquals('0', $result['result_code']);

        return $tradeNo;
    }*/

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    /*public function testDigitalPaymentOrderFind($tradeNo)
    {
        $tradeQuery = new TradeQuery($this->merchantId, $this->merchantPrivateKey);
        $result = $tradeQuery->find($tradeNo);

        $this->assertEquals('T', $result['is_success']);
    }*/

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    /*public function testDigitalPaymentOrderIsPaid($tradeNo)
    {
        $tradeQuery = new TradeQuery($this->merchantId, $this->merchantPrivateKey);
        $result = $tradeQuery->isPaid($tradeNo);

        $this->assertFalse($result);
    }*/

    /*public function testBankPaymentOrder()
    {
        $faker = Factory::create();
        $tradeNo = str_replace('-', '', $faker->uuid);
        $bank = Bank::CEBB;
        $amount = 1;
        $notifyUrl = $faker->url;
        $returnUrl = $faker->url;

        $payment = new BankPayment($this->merchantId, $this->merchantPrivateKey);
        $result = $payment->order($tradeNo, $bank, $amount, $notifyUrl, $returnUrl);

        $this->assertContains('<form', $result, '', true);

        return $tradeNo;
    }*/

    /*public function testTradeQueryFindOrderNotExist()
    {
        $faker = Factory::create();
        $tradeNo = str_replace('-', '', $faker->uuid);

        $tradeQuery = new TradeQuery($this->merchantId, $this->merchantPrivateKey);
        $result = $tradeQuery->find($tradeNo);

        $this->assertNull($result);
    }*/

    /*public function testTradeQueryIsPaidOrderNotExist()
    {
        $faker = Factory::create();
        $tradeNo = str_replace('-', '', $faker->uuid);

        $tradeQuery = new TradeQuery($this->merchantId, $this->merchantPrivateKey);
        $result = $tradeQuery->isPaid($tradeNo);

        $this->assertFalse($result);
    }*/

/*    public function testNotifyWebhookVerifyNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'trade_no'          => 'C1072507896',
            'orginal_money'     => '1',
            'sign_type'         => 'RSA-S',
            'notify_type'       => 'offline_notify',
            'merchant_code'     => '1111110166',
            'order_no'          => '1507174877',
            'trade_status'      => 'SUCCESS',
            'sign'              => 'HIMvcuezx2GvwpIlPtNfqF6zsWAz1Pzf1zFjjKHPmFiXW419wWK/DpaeR02K570XTVW+2cWYoouiiVq8dNJnL0zy8EeVsPrf4vkh+2o0KWd8XiDBtdpRwC58dG/DRjVZ3uPovNTPIbIs+A8sJQR5rhLOXkfPQM4DfGVGqPLw10s=',
            'order_amount'      => '1',
            'interface_version' => 'V3.3',
            'bank_seq_no'       => 'W17720171005114118147838',
            'order_time'        => '2017-10-05 11:41:17',
            'notify_id'         => '3b51831c178249cdb824a0eab4d1c3d3',
            'trade_time'        => '2017-10-05 11:41:18',
        ];
        $dinpayPublicKey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCJQIEXUkjG2RoyCnfucMX1at7O
PtOCDSiKZhtzHw5HOjXKteBpYBqEBOZc9pNjP/fKbvBNZ3Z7XxUn5ECfQbPCtH9y
++c0WxAYPoZiPDEYeQmRJfqPR68c0aAtZN5Kh7H1SI2ZRvoMUdZGvvFy3vuPnTwm
3R+aHq17bch/0ZAudwIDAQAB
-----END PUBLIC KEY-----';

        $this->assertTrue($mock->verifyNotifyPayload($payload, $dinpayPublicKey));
    }
*/
/*    public function testNotifyWebhookParseNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'trade_no'          => 'C1072507896',
            'orginal_money'     => '1',
            'sign_type'         => 'RSA-S',
            'notify_type'       => 'offline_notify',
            'merchant_code'     => '1111110166',
            'order_no'          => '1507174877',
            'trade_status'      => 'SUCCESS',
            'sign'              => 'HIMvcuezx2GvwpIlPtNfqF6zsWAz1Pzf1zFjjKHPmFiXW419wWK/DpaeR02K570XTVW+2cWYoouiiVq8dNJnL0zy8EeVsPrf4vkh+2o0KWd8XiDBtdpRwC58dG/DRjVZ3uPovNTPIbIs+A8sJQR5rhLOXkfPQM4DfGVGqPLw10s=',
            'order_amount'      => '1',
            'interface_version' => 'V3.3',
            'bank_seq_no'       => 'W17720171005114118147838',
            'order_time'        => '2017-10-05 11:41:17',
            'notify_id'         => '3b51831c178249cdb824a0eab4d1c3d3',
            'trade_time'        => '2017-10-05 11:41:18',
        ];
        $dinpayPublicKey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCJQIEXUkjG2RoyCnfucMX1at7O
PtOCDSiKZhtzHw5HOjXKteBpYBqEBOZc9pNjP/fKbvBNZ3Z7XxUn5ECfQbPCtH9y
++c0WxAYPoZiPDEYeQmRJfqPR68c0aAtZN5Kh7H1SI2ZRvoMUdZGvvFy3vuPnTwm
3R+aHq17bch/0ZAudwIDAQAB
-----END PUBLIC KEY-----';

        $this->assertEquals($payload, $mock->parseNotifyPayload($payload, $dinpayPublicKey));
    }

    public function testNotifyWebhookSuccessNotifyResponse()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $this->assertEquals('SUCCESS', $mock->successNotifyResponse());
    }
    */
}
