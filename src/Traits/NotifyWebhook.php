<?php

namespace Jetfuel\Verypay\Traits;

use Jetfuel\Verypay\Signature;
use Jetfuel\Verypay\RsaCrypt;

trait NotifyWebhook
{
    use ConvertMoney;

    /**
     * Verify notify request's signature.
     *
     * @param $payload
     * @param $privateKey
     * @param $secretKey
     * @return bool
     */
    public function verifyNotifyPayload($payload, $privateKey, $secretKey)
    {
        if (!isset($payload['data']))
        {
            return false;
        }
        else
        {
            $data = urldecode($payload['data']);
            $data = RsaCrypt::rsaDecrypt($data, $privateKey);
            $aryData = json_decode($data,true);
            $signature = $aryData['sign'];
            unset($aryData['sign']);
            return Signature::validate($aryData, $secretKey, $signature);
        }
    }

    /**
     * Verify notify request's signature and parse payload.
     *
     * @param $payload
     * @param $secretKey
     * @return array|null
     */
    public function parseNotifyPayload($payload, $secretKey)
    {
        if (!$this->verifyNotifyPayload($payload, $secretKey)) {
            return null;
        }

        return $payload;
    }

    /**
     * Response content for successful notify.
     *
     * @return string
     */
    public function successNotifyResponse()
    {
        return '0';
    }
}
