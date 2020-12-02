<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Application;


use IturDev\ApiCommand;

class AddCouponOrderCommand implements ApiCommand
{
    public $email;
    public $price;
    public $session_user_id;
    public $user_profile_id;
    public $time;

    public static function getDefinition()
    {
        return [
            "email"=>"(string) Email",
            "price"=>"(float) Price",
            "session_user_id"=>"(string) SessionUserId",
            "user_profile_id"=>"(string) UserProfileId",
            "time"=>"(int) timestamp"
        ];


    }

}