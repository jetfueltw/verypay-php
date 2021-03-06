<?php

namespace Jetfuel\Verypay;

use Jetfuel\Verypay\Traits\ConvertMoney;

class Payment
{
    use ConvertMoney;

    const API_VERSION = 'V3.1.0.0';
    const CHARSET     = 'UTF-8';
    const GOODS_NAME  = 'GOODS_NAME';

    /**
     * @var string
     */
    protected $merchantId;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $privateKey;

    /**
     * @var string
     */
    protected $publicKey;

    /**
     * @var string
     */
    protected $baseApiUrl;

    /**
     * @var \Jetfuel\Verypay\HttpClient\HttpClientInterface
     */
    protected $httpClient;

    /**
     * Payment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey for sign
     * @param string $privateKey
     * @param string $publicKey
     * @param string $baseApiUrl
     */
    protected function __construct($merchantId, $secretKey, $privateKey, $publicKey, $baseApiUrl)
    {
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->baseApiUrl = $baseApiUrl;
    }

    /**
     * Sign request payload.
     *
     * @param array $payload
     * @return string
     */
    protected function signPayload(array $payload)
    {
        $payload['merNo'] = $this->merchantId;
        $payload['goodsName'] = self::GOODS_NAME;
        $payload['sign'] = Signature::generate($payload, $this->secretKey);

        $data = RsaCrypt::encrypt($payload, $this->publicKey);

        return 'data='.$data.'&merchNo='.$this->merchantId.'&version='.self::API_VERSION;
    }
}
