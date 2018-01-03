<?php

namespace Jetfuel\Verypay;

use Jetfuel\Verypay\Traits\ResultParser;

class DigitalPayment extends Payment
{
    use ResultParser;

    /**
     * DigitalPayment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantNo, $md5Key, $privateKey, $payPublicKey, $remitPublicKey, $baseApiUrl = null)
    {
        parent::__construct($merchantNo, $md5Key, $privateKey, $payPublicKey, $remitPublicKey, $baseApiUrl);
    }

    /**
     * Create digital payment order.
     *
     * @param string $tradeNo
     * @param int $channel
     * @param float $amount
     * @param string $clientIp
     * @param string $notifyUrl
     * @return array
     */
    public function order($tradeNo, $channel, $amount, $clientIp, $goodsName, $notifyUrl, $returnUrl)
    {
        $payload = $this->signPayload([
            'orderNum'        => $tradeNo,
            'random'          => /*(string) rand(1000,9999)*/'52ZI',
            'amount'          => (string)$this->convertYuanToFen($amount),
            'netway'          => $channel,
            'goodsName'       => $goodsName,
            'callBackUrl'     => $notifyUrl,
            'callBackViewUrl' => $returnUrl,
        ]);

var_dump($payload);

        return $this->parseResponse($this->httpClient->post('api/pay.action', $payload));
    }
}
