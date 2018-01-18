<?php

namespace Jetfuel\Verypay\Constants;

class BaseUrl
{
    const TRADE_QUERY     = 'http://payquery.qyfpay.com:90/';
    const DIGITAL_PAYMENT = [
        Channel::WECHAT   => 'http://wx.qyfpay.com:90/',
        Channel::ALIPAY   => 'http://zfb.qyfpay.com:90/',
        Channel::QQ       => 'http://qq.qyfpay.com:90/',
        Channel::JDPAY    => 'http://jd.qyfpay.com:90/',
        Channel::UNIONPAY => 'http://unionpay.qyfpay.com:90/',
        Channel::BAIDU    => 'http://baidu.qyfpay.com:90/',
    ];
}
