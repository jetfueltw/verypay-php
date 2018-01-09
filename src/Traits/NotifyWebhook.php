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
            $data = RsaCrypt::rsaDecrypt(urldecode($payload['data']), $privateKey);
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
     * @param $privateKey
     * @param $secretKey
     * @return array|null
     */
    public function parseNotifyPayload($payload, $privateKey, $secretKey)
    {
        if (!isset($payload['data']))
        {
            return null;
        }
  
        $data = RsaCrypt::rsaDecrypt(urldecode($payload['data']), $privateKey);
        $aryData = json_decode($data,true);
        $signature = $aryData['sign'];
        unset($aryData['sign']);

        if (!Signature::validate($aryData, $secretKey, $signature))
        {
            return null;
        }

        $aryData['amount'] = $this->convertFenToYuan($aryData['amount']);

        return $aryData;
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
