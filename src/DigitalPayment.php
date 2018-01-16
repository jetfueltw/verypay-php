<?php

namespace Jetfuel\Verypay;

use Jetfuel\Verypay\Constants\BaseUrl;
use Jetfuel\Verypay\HttpClient\CurlHttpClient;
use Jetfuel\Verypay\Traits\ResultParser;

class DigitalPayment extends Payment
{
    use ResultParser;

    const API_VERSION = 'V3.1.0.0';
    const CHARSET     = 'UTF-8';

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
     * Create digital payment order.
     *
     * @param string $tradeNo
     * @param string $channel
     * @param float $amount
     * @param string $clientIp
     * @param string $notifyUrl
     * @param string $returnUrl
     * @return array
     */
    public function order($tradeNo, $channel, $amount, $clientIp, $notifyUrl, $returnUrl)
    {
        if ($this->baseApiUrl === null) {
            $this->baseApiUrl = BaseUrl::DIGITAL_PAYMENT[$channel];
        }

        $this->httpClient = new CurlHttpClient($this->baseApiUrl);

        $payload = $this->signPayload([
            'orderNum'        => $tradeNo,
            'random'          => (string)rand(1000, 9999),
            'amount'          => (string)$this->convertYuanToFen($amount),
            'netway'          => $channel,
            'callBackUrl'     => $notifyUrl,
            'callBackViewUrl' => $returnUrl,
            'version'         => self::API_VERSION,
            'charset'         => self::CHARSET,
        ], $this->publicKey);

        return $this->parseResponse($this->httpClient->post('api/pay.action', $payload), $this->secretKey);
    }
}
