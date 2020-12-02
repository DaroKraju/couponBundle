<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Application;


use IturDev\ApiCommand;

class UpdateCouponOrderFinalizedCommand implements ApiCommand
{
    public $coupon_id;

    public $payment_id;

    public $timestamp;

    public static function getDefinition()
    {
        return [
            "coupon_id"=>"(string) CouponId",
            "payment_id"=>"(string) PaymentId",
            "timestamp"=>"(int) timestamp",
        ];
    }
}