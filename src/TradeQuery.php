<?php

namespace Jetfuel\Verypay;

use Jetfuel\Verypay\Traits\ResultParser;

class TradeQuery extends Payment
{
    use ResultParser;

    /**
     * DigitalPayment constructor.
     *
     * @param string $merchantId
     * @param string $md5Key
     * @param string $privateKey
     * @param string $payPublicKey
     * @param string $remitPublicKey
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantNo, $md5Key, $privateKey, $payPublicKey, $remitPublicKey, $baseApiUrl = null)
    {
        parent::__construct($merchantNo, $md5Key, $privateKey, $payPublicKey, $remitPublicKey, $baseApiUrl);
    }

    /**
     * Find Order by trade number.
     *
     * @param string $tradeNo
     * @return array|null
     */
    public function find($tradeNo, $channel, $amount, $goodsName, $payDate)
    {
        $payload = $this->signQueryPayload([
            'orderNum'          => $tradeNo,
            'netway'            => $channel,
            'amount'            => (string)$this->convertYuanToFen($amount),
            'goodsName'         => $goodsName,
            'payDate'           => $payDate,

        ]);
        var_dump($payload);

        $payload = $this->rsaEncrypt($payload);

        $data = 'data=' . urlencode($payload) . '&merchNo=' . 'QYF201705200001' . '&version=V3.1.0.0';
        var_dump($data);

        $order = $this->parseResponse($this->httpClient->post('api/queryPayResult.action', $data));

        /*if ($order['is_success'] !== 'T') {
            return null;
        }*/

        return $order;
    }

    /**
     * Is order already paid.
     *
     * @param string $tradeNo
     * @return bool
     */
    /*public function isPaid($tradeNo)
    {
        $order = $this->find($tradeNo);

        if ($order === null || !isset($order['data']['replyCode']) || $order['data']['replyCode'] !== '00') {
            return false;
        }

        return true;
    }*/
}
