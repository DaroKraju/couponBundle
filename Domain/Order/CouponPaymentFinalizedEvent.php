<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order;


use IturDev\Domain\DomainEvent;

class CouponPaymentFinalizedEvent extends DomainEvent
{
    private $couponId;
    private $hash;

    /**
     * CouponPaymentFinalizedEvent constructor.
     * @param $couponId
     * @param $hash
     */
    public function __construct($couponId, $hash)
    {
        $this->couponId = $couponId;
        $this->hash = $hash;
        parent::__construct([]);

    }

    /**
     * @return mixed
     */
    public function getCouponId()
    {
        return $this->couponId;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }


}