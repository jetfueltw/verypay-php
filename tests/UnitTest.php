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
    private $merchantId;
    private $secretKey;
    private $merchantPrivateKey;
    private $merchantPayPublicKey;
    private $merchantRemitPublicKey;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->merchantId = getenv('MERCHANT_NO');
        $this->secretKey = getenv('MD5_KEY');
        //$this->merchantPrivateKey = getenv('MERCHANT_PRIVATE_KEY');
        //$this->merchantPayPublicKey = getenv('MERCHANT_PAY_PUBLIC_KEY');
        //$this->merchantRemitPublicKey = getenv('MERCHANT_REMIT_PUBLIC_KEY');

        $this->merchantPrivateKey = '-----BEGIN RSA PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAI571YkVYHVb4TvTtJxcVVByWRHF
5se8xDDZZvPA1HlU0tj7bYSdzZ4iluXtj3FKQFQjU4tNgbBaXQMHJQOKRCbOQUhODth1K0FHZtrT
01eVcXfEcsd0m608vhLjx87Rr6wzJjR+gpq4DT0mAQGxf4wHEPBu5GBZ5aIbwak5ODXBAgMBAAEC
gYAbvUIiURYZYwzjj+DOvC8j3U835ZZ7dmWfuQORGw6CnJ/7/F8i/XHlgohsNSbDAJiriMEgErPX
+I+5Ii/zk3yW4xEoqkHrHRZGJTGNP2VgwnF25Nr1mDfslI71DJqdZFnl7ZUQcEP3n/IzzvxNYFQ9
yhYAmxV849QycaNgunZ1AQJBAMXye/QX0aezBdvg0zTzNxTA6SHhfUzvGpVIL+2GVTDlmYsU46d2
nKOVwOEE20QYLfYiLBZMAqwmi/t8Mt9TYNECQQC4RUDGqVSNMi7e1seTljol/qP6HGLe3tujZY/n
mHQr2VYQFLnuF7EyBl41nQ7rX/s+OM9wnDgC/21UJBuvFUHxAkAaBIMyVCckaa1tdyGLpiQpQCnk
YCT+Bbdyw6g5Ch0MbkE+PKKnkjmIbtiJOwAu9RalcVxmGduIERD5Hxv4qpbhAkEAn/GUkRtnRYt6
fXfmEWfDHzmQsUa0VwkPkhtUtkxxAaKK/jhPTqeH6Yj3ewfRbGKKXG7JN9CRGaEGD5Or5+PGsQJB
AJnaN/AJdu+G09DnjsgU1uT8cfwqjztAWaV0ctRWkylOonCgo14/iBqEkDfeDNsda8oEknl42ZX/
UJgJMtPwx/g=
-----END RSA PRIVATE KEY-----';
        $this->merchantPayPublicKey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBteAp6tB90/OG6C6M5RhgKHbGajBZSumfk1st
d7oihCo+ZOL70GNdyysjduo5jyMy11Sc2cVrl2xmw4oN6IPq2GvP3hSTFpOmo8wnIBcsBecQFcWI
9bUOsJMXSyBYsHJrLHGzG+UAcFn/ZAVTWaI/7RRrhpfnWPo65dXpdFmsEQIDAQAB
-----END PUBLIC KEY-----';
        $this->merchantRemitPublicKey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCS04Bl4sW5o+xSh62l86FLbJxY8Gf7ImT9117R
Y3TeUlICjiBTkUUJn/q/jQhSppja1jCU02zoS4g5Jq7ZeFaxdDeNkWqvfF76YC7U7lE+S6b9Wv/k
6w9UmvZYafTNbltszdMFY4coDHaL44grLRHMonMEa+gX1rt1WYORiaJLJwIDAQAB
-----END PUBLIC KEY-----';
    }

    /*public function testSign()
    {
        //$tradeNo = '201712281510357829';
        $tradeNo = date('YmdHis').rand(10000, 99999);
        $channel = Channel::ALIPAY;
        $amount = 1;
        $clientIp = '127.0.0.1';
        $notifyUrl = 'https://www.tencent.com';
        $returnUrl = 'https://www.tencent.com';

        $payment = new DigitalPayment($this->merchantId, $this->secretKey, $this->merchantPrivateKey, $this->merchantPayPublicKey);

        $result = $payment->order($tradeNo, $channel, $amount, $clientIp, $notifyUrl, $returnUrl);
        var_dump($result);
    }*/

    public function testDigitalPaymentOrder()
    {
        $faker = Factory::create();
        $tradeNo = date('YmdHis').rand(10000, 99999);
        $channel = Channel::ALIPAY;
        $amount = 1;
        $clientIp = $faker->ipv4;
        $notifyUrl = $faker->url;
        $returnUrl = $faker->url;

        // $tradeNo = date('YmdHis').rand(10000, 99999);
        // $channel = Channel::ALIPAY;
        // $amount = 1;
        // $clientIp = '127.0.0.1';
        // $notifyUrl = 'https://www.tencent.com';
        // $returnUrl = 'https://www.tencent.com';

        $payment = new DigitalPayment($this->merchantId, $this->secretKey, $this->merchantPrivateKey, $this->merchantPayPublicKey);
        $result = $payment->order($tradeNo, $channel, $amount, $clientIp, $notifyUrl, $returnUrl);

        var_dump($result);

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
        $channel = Channel::ALIPAY;
        $amount = 1;
        $payDate = date('Y-m-d');

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey, $this->merchantPrivateKey, $this->merchantPayPublicKey); 
        $result = $tradeQuery->find($tradeNo, $channel, $amount, $payDate);
        

        var_dump($result);
        $this->assertEquals('00', $result['stateCode']);
    }

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    public function testDigitalPaymentOrderIsPaid($tradeNo)
    {
        $channel = Channel::ALIPAY;
        $amount = 1;
        $payDate = '2018-01-05';

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey, $this->merchantPrivateKey, $this->merchantPayPublicKey);
        $result = $tradeQuery->isPaid($tradeNo, $channel, $amount, $payDate);

        var_dump($result);

        $this->assertFalse($result);
    }

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
