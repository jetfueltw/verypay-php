<?php

namespace Jetfuel\Verypay;

class RsaCrypt
{
    /**
     * @param array $payload
     * @param string $publicKey
     * @return string
     */
    public static function encrypt($payload, $publicKey)
    {
        $baseString = json_encode($payload, 320);
        $publicKey = openssl_pkey_get_public($publicKey);

        $encryptData = '';
        foreach (str_split($baseString, 117) as $chunk) {
            openssl_public_encrypt($chunk, $crypted, $publicKey);
            $encryptData .= $crypted;
        }

        return urlencode(base64_encode($encryptData));
    }

    /**
     * @param string $data
     * @param string $privateKey
     * @param bool $urlDecode
     * @return array
     */
    public static function decrypt($data, $privateKey, $urlDecode)
    {
        if ($urlDecode) {
            $data = urldecode($data);
        }

        $data = base64_decode($data);
        $privateKey = openssl_get_privatekey($privateKey);

        $decryptData = '';
        foreach (str_split($data, 128) as $chunk) {
            openssl_private_decrypt($chunk, $decrypted, $privateKey);
            $decryptData .= $decrypted;
        }

        return json_decode($decryptData, true);
    }
}
