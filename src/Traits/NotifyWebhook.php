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
        if (!isset($payload['data'])) {
            return false;
        }

        $data = $this->getDecryptData($payload['data'], $privateKey);
        $signature = $data['sign'];

        unset($data['sign']);

        return Signature::validate($data, $secretKey, $signature);
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
        if (!isset($payload['data'])) {
            return null;
        }

        $data = $this->getDecryptData($payload['data'], $privateKey);
        $signature = $data['sign'];

        unset($data['sign']);

        if (!Signature::validate($data, $secretKey, $signature)) {
            return null;
        }

        $data['amount'] = $this->convertFenToYuan($data['amount']);
        $data['sign'] = $signature;

        return $data;
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

    /**
     * @param string $data
     * @param string $privateKey
     * @return array
     */
    private function getDecryptData($data, $privateKey)
    {
        return RsaCrypt::decrypt($data, $privateKey);
    }
}
