<?php

namespace Jetfuel\Verypay;

use Jetfuel\Verypay\HttpClient\GuzzleHttpClient;
use Jetfuel\Verypay\Traits\ConvertMoney;

class Payment
{
    use ConvertMoney;
    const BASE_API_URL = 'http://139.199.195.194:8080/';
    const API_VERSION = 'V3.1.0.0';
    const CHARSET = 'UTF-8';


    /**
     * @var string
     */
    protected $merchantNo;

    /**
     * @var string
     */
    protected $md5Key;

    /**
     * @var string
     */
    protected $privateKey;

    /**
     * @var string
     */
    protected $payPublicKey;

    /**
     * @var string
     */
    protected $remitPublicKey;

    /**
     * @var string
     */
    protected $baseApiUrl;

    /**
     * @var \Jetfuel\Wefupay\HttpClient\HttpClientInterface
     */
    protected $httpClient;

    /**
     * Payment constructor.
     *
     * @param string $merchantNo
     * @param string $md5Key for sign
     * @param string $privateKey
     * @param string $payPublicKey
     * @param string $remitRublicKey
     * @param string $baseApiUrl
     */
    protected function __construct($merchantNo, $md5Key, $privateKey, $payPublicKey, $remitPublicKey, $baseApiUrl = null)
    {
        $this->merchantNo = $merchantNo;
        $this->md5Key = $md5Key;
        $this->privateKey = $privateKey;
        $this->payPublicKey = $payPublicKey;
        $this->remitPublicKey = $remitPublicKey;
        $this->baseApiUrl = $baseApiUrl === null ? self::BASE_API_URL : $baseApiUrl;

        $this->httpClient = new GuzzleHttpClient($this->baseApiUrl);
    }

    /**
     * Sign request payload.
     *
     * @param array $payload
     * @return string
     */
    protected function signPayload(array $payload)
    {
        $payload['merNo'] = $this->merchantNo;
        $payload['version'] = self::API_VERSION;
        $payload['charset'] = self::CHARSET;
        ksort($payload);
        $payload['sign'] = Signature::generate($payload, $this->md5Key);
        
        return json_encode($payload, 320);

        // var_dump($payload);
        // echo 'Payload = ' . $payload;

        // return $payload;
    }

    /**
     * Get current time.
     *
     * @return string
     */
    protected function getCurrentTime()
    {
        return (new \DateTime('now', new \DateTimeZone(self::TIME_ZONE)))->format(self::TIME_FORMAT);
    }
}
