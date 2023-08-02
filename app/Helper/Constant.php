<?php

namespace App\Helper;

class Constant
{
    public const ORDER_STATUS = [
        0 => 'Cancel',
        10 => 'To Pay ',
        20 => 'To Ship',
        30 => 'To Receive',
        35 => 'Delivered',
        40 => 'Completed'
    ];

    public const PAYMENT_CODE = [
        'cards'   => 'Credit/Debit Card',
        'bakong' => 'kHQRs',
        'abapay'   => 'ABA Pay',
        'predepost' => 'Wallet',
        'pipay'   => 'Pipay',
        'cash' => 'Cash On Delivery',
        'aeon'   => 'AEON Pay',
        'aeonCredit' => 'AEON Credit Card',
        'payway' => 'Payway',
        'paypal' => 'Paypal',
    ];

    public const BILL_CYCLE = [
        1 => 'CoD',
        2 => 'T+1',
        11 => 'Standard',
        16 => 'Standard'
    ];

    public const ORDER_TYPE = [
        1 => 'Normal',
        2 => 'Group buy',
        4 => 'Prize',
        5 => 'Charity',
        6 => 'Get it free',
    ];
}
