<?php

namespace Jetfuel\Verypay;

class RsaCrypt
{
    /**
     * Generate signature.
     *
     * @param array $payload
     * @param string $secretKey
     * @return string
     */
     public static function rsaEncrypt($data, $publicKey)
     {
        $publicKey = openssl_pkey_get_public($publicKey);
        $encryptData = '';
        $crypto = '';
        //var_dump($publicKey);
        //$data = 'this is a key';
        //var_dump(openssl_public_encrypt($data, $crypto, $publicKey));
        foreach(str_split($data, 117) as $chunk) 
        {
            //var_dump($chunk);
            openssl_public_encrypt($chunk, $encryptData, $publicKey);
            $crypto = $crypto . $encryptData;
            //var_dump($crypto);
        }

        $crypto = base64_encode($crypto);
		return $crypto;
     }

     public static function rsaDecrypt($data)
     {

     }
}