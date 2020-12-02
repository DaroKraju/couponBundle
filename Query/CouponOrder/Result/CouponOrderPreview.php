<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Query\CouponOrder\Result;


use IturDev\Query\Spec\ODM\Result\Formattable;
use IturDev\Query\Spec\ODM\Result\ResultODMFormatter;
use IturDev\ViewModel\ViewModel;
use Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order\CouponOrder;

class CouponOrderPreview extends ResultODMFormatter
{
    public function isCacheEnabled()
    {
        return false;
    }

    /**
     * @param Formattable|CouponOrder $couponOrder
     * @return ViewModel
     */
    protected function doFormat(Formattable $couponOrder)
    {
        return new ViewModel([
           "id" => $couponOrder->getAggregateId(),
           "price" => $couponOrder->getPrice()->getPrice(),
           "currency" => $couponOrder->getPrice()->getCurrency(),
           "hash" => $couponOrder->getHash(),
           "expiryAt" => $couponOrder->getCouponOrderStatus()->getExpiryAt(),
           "status" =>$couponOrder->getCouponOrderStatus()->getStatus(),
           "paymentUrl" => $couponOrder->getPaymentStatus()->getUrl(),
           "email" => $couponOrder->getPersonalData()->getEmail()->getEmail()
        ]);
    }

}