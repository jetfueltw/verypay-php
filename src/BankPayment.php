<?php

namespace Jetfuel\Verypay;

class BankPayment extends Payment
{
    const BANK_CARD_TYPE = '01';

    /**
     * BankPayment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantId, $secretKey, $privateKey, $publicKey, $baseApiUrl = null)
    {
        parent::__construct($merchantId, $secretKey, $privateKey, $publicKey, $baseApiUrl);
    }

    /**
     * Create bank payment order.
     *
     * @param string $tradeNo
     * @param string $bank
     * @param float $amount
     * @param string $notifyUrl
     * @param string $returnUrl
     * @return string
     */
    public function order($tradeNo, $bank, $amount, $notifyUrl)
    {
        $payload = $this->signPayload([
            'orderNum'        => $tradeNo,
            'amount'          => (string)$this->convertYuanToFen($amount),
            'bankCode'        => $bank,
            'bankAccountName' => '户名',
            'bankAccountNo'   => '6217002200015935552',
            'callBackUrl'     => $notifyUrl,
        ], $this->publicKey);

        return $this->httpClient->post('api/remit.action', $payload);
    }
}
