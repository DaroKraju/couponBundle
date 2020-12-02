<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Application;


use IturDev\ApiCommand;

class RejectCouponOrderByInvalidTransactionDataCommand implements ApiCommand
{
    public $coupon_id;
    public $time;

    public static function getDefinition()
    {
        return [
            "coupon_id"=>"(string) CouponId",
            "time"=>"(int) timestamp",
        ];
    }

}