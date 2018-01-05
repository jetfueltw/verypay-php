<?php

namespace Jetfuel\Verypay;

class RsaCrypt
{
    /**
     * Generate rsaEncrypt
     *
<<<<<<< HEAD
     * @param array $data
=======
     * @param array $payload
>>>>>>> 731f13603c6ffa973d94e4fef0fa28a6c4782287
     * @param string $publicKey
     * @return string
     */
    public static function rsaEncrypt($payload, $publicKey)
    {
        $baseString = json_encode($payload, 320);

        $publicKey = openssl_pkey_get_public($publicKey);

        $encryptChunk = '';
        $data = '';
        foreach (str_split($baseString, 117) as $chunk) {
            openssl_public_encrypt($chunk, $encryptChunk, $publicKey);
            $data .= $encryptChunk;
        }

        return urlencode(base64_encode($data));
    }

    public static function rsaDecrypt($data)
    {

    }
}
