<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Domain;


use IturDev\Domain\AggregateRoot;
use IturDev\Domain\DomainRepository;
use Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order\CouponOrder;

interface CouponOrderRepository extends DomainRepository
{
    public function findById($id) : ?CouponOrder;

    public function findByHash($id) : ?CouponOrder;

    /**
     * @param CouponOrder $couponOrder
     */
    public function save(AggregateRoot $couponOrder);
}