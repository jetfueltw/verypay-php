<?php

namespace Jetfuel\Verypay;

use Jetfuel\Verypay\Traits\ResultParser;

class TradeQuery extends Payment
{
    use ResultParser;

    const GOODS_NAME = 'GOODS_NAME';

    /**
     * DigitalPayment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param string $privateKey
     * @param string $publicKey
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantId, $secretKey, $privateKey, $publicKey, $baseApiUrl = null)
    {
        parent::__construct($merchantId, $secretKey, $privateKey, $publicKey, $baseApiUrl);
    }

    /**
     * Find Order by trade number.
     *
     * @param string $tradeNo
     * @param string $channel
     * @param string $amount
     * @param string $payDate
     * @return array|null
     */
    public function find($tradeNo, $channel, $amount, $payDate)
    {
        $payload = $this->signQueryPayload([
            'orderNum'  => $tradeNo,
            'netway'    => $channel,
            'amount'    => (string)$this->convertYuanToFen($amount),
            'goodsName' => self::GOODS_NAME,
            'payDate'   => $payDate,

        ], $this->publicKey);

        $order = $this->parseResponse($this->httpClient->post('api/queryPayResult.action', $payload), $this->secretKey);

        if ($order['stateCode'] !== '00') {
            return null;
        }

        return $order;
    }

    /**
     * Is order already paid.
     *
     * @param string $tradeNo
     * @return bool
     */
    public function isPaid($tradeNo, $channel, $amount, $payDate)
    {
        $order = $this->find($tradeNo, $channel, $amount, $payDate);

        if ($order === null || !isset($order['payStateCode']) || $order['payStateCode'] !== '00') {
            return false;
        }

        return true;
    }
}
