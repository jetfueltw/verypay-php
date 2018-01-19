<?php

namespace Jetfuel\Verypay\Traits;

use Jetfuel\Verypay\Signature;

trait ResultParser
{
    /**
     * Parse JSON format response to array.
     *
     * @param string $response
     * @param string $secretKey
     * @return array
     */
    public function parseResponse($response, $secretKey)
    {
        $result = json_decode($response, true);

        if (isset($result['sign'])) {
            $signature = $result['sign'];
            unset($result['sign']);

            if (!Signature::validate($result, $secretKey, $signature)) {
                return [
                    'stateCode' => '03',
                    'msg'       => '签名错误',
                ];
            }

            $result['sign'] = $signature;
        }

        return $result;
    }
}
