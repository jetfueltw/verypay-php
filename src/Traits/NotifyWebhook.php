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
     * @param array $payload
     * @param string $secretKey
     * @param string $privateKey
     * @param bool $urlDecode In PHP, $_GET and $_POST are already decoded.
     * @return bool
     */
    public function verifyNotifyPayload(array $payload, $secretKey, $privateKey, $urlDecode = false)
    {
        if (!isset($payload['data'])) {
            return false;
        }

        $data = $this->getDecryptData($payload['data'], $privateKey, $urlDecode);
        $signature = $data['sign'];

        unset($data['sign']);

        return Signature::validate($data, $secretKey, $signature);
    }

    /**
     * Verify notify request's signature and parse payload.
     *
     * @param array $payload
     * @param string $secretKey
     * @param string $privateKey
     * @param bool $urlDecode In PHP, $_GET and $_POST are already decoded.
     * @return array|null
     */
    public function parseNotifyPayload(array $payload, $secretKey, $privateKey, $urlDecode = false)
    {
        if (!isset($payload['data'])) {
            return null;
        }

        $data = $this->getDecryptData($payload['data'], $privateKey, $urlDecode);
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
     * @param bool $urlDecode
     * @return array
     */
    private function getDecryptData($data, $privateKey, $urlDecode)
    {
        return RsaCrypt::decrypt($data, $privateKey, $urlDecode);
    }
}
