## 介紹

輕易付聚合支付 PHP 版本封裝。

## 安裝

使用 Composer 安裝。

```
composer require jetfueltw/verypay-php
```

## 使用方法

### 掃碼支付下單

使用微信支付、QQ錢包、支付寶掃碼支付，下單後返回支付網址，請自行轉為 QR Code。

```
$merchantId = 'XXXXXXXXXXXXXXX'; // 商家號
$secretKey = 'XXXXXXXXXXXXXXX'; // md5 密鑰
$merchantPrivateKey = '-----BEGIN RSA PRIVATE KEY-----XXXXXXXXXX-----BEGIN RSA PRIVATE KEY-----' //RSA密鑰
merchantPayPublicKey = '-----BEGIN PUBLIC KEY-----XXXXXXXXXX-----BEGIN PUBLIC KEY-----' //RSA公鑰
$tradeNo = '20180109023351XXXXX'; // 商家產生的唯一訂單號
$channel = Channel::WECHAT; // 支付通道，支援微信支付、QQ錢包、支付寶
$amount = 1.00; // 消費金額 (元)
$clientIp = 'XXX.XXX.XXX.XXX'; // 消費者端 IP 位址
$notifyUrl = 'https://XXX.XXX.XXX'; // 交易完成後異步通知接口
$returnUrl = 'https://XXX.XXX.XXX'; // 交易完成後會跳轉到這個頁面
```
```
$payment = new DigitalPayment(merchantId, secretKey, merchantPrivateKey, merchantPayPublicKey);
$result = $payment->order($tradeNo, $channel, $amount, $clientIp, $notifyUrl, $returnUrl);
```
```
Result:
[
    'merNo' => 'XXXXXXXXXXXXXXX', // 商家號
    'stateCode' => 'XX', // 00表示成功
    'msg'=> 'XXXX'; // 狀態描述
    'orderNum'=>'20180109023351XXXXX', // 商家產生的唯一訂單號
    'qrcodeUrl'=>'https://qr.alipay.com/upx07533duhp4xmuuXXXXXXX', // 下單時間
    'sign'=> '1C1E6B6DCD8DC9F70565AFXXXXXXXXXX', // 簽名(字母大寫)
];
```

### 掃碼支付交易成功通知

消費者支付成功後，平台會發出 HTTP POST 請求到你下單時填的 $notifyUrl，商家在收到通知並處理完後必須回應 `{"code":"00"}`，否則平台會認為通知失敗，並重發多次通知。