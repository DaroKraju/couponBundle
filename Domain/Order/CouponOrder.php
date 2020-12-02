<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use IturDev\Domain\AggregateRoot;
use IturDev\Domain\Money\Price;

/**
 * @ORM\Entity()
 * @ORM\Table(name="coupon_orders", indexes={})
 */
class CouponOrder extends AggregateRoot
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Price
     * @ORM\Embedded(class="IturDev\Domain\Money\Price", columnPrefix=false)
     */
    protected $price;

    /**
     * @var PersonalData
     * @ORM\Embedded(class="Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order\PersonalData", columnPrefix=false)
     */
    protected $personalData;

    /**
     * @var PaymentStatus
     * @ORM\Embedded(class="Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order\PaymentStatus", columnPrefix="payment_")
     */
    protected $paymentStatus;

    /**
     * @var CouponOrderStatus
     * @ORM\Embedded(class="Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order\CouponOrderStatus", columnPrefix=false)
     */
    protected $couponStatus;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected  $hash;

    /**
     *  @ORM\Column(type="datetime", nullable=true)
     *  @Gedmo\Timestampable(on="update")
     */
    protected $updated_at;

    /**
     * CouponOrder constructor.
     * @param Price $price
     * @param PersonalData $personalData
     * @param PaymentStatus $paymentStatus
     * @param CouponOrderStatus $couponStatus
     * @param string $hash
     */
    public function __construct(Price $price, PersonalData $personalData, \DateTime $datetime)
    {
        $this->price = $price;
        $this->personalData = $personalData;
        $this->couponStatus = CouponOrderStatus::newWithDefaultExpiry($datetime);
        $this->paymentStatus = PaymentStatus::blank();
        $this->hash = $this->generateHash();
    }

    public function markAsPaid(\DateTime $dateTime)
    {
        $this->couponStatus=$this->couponStatus->nowPaid($dateTime);
        $this->raise(new CouponPaymentFinalizedEvent(
            $this->getId(),
            $this->getHash()
        ));
    }

    public function readyToPay(array $paymentUrl, \DateTime $paymentExpiryAt)
    {
        $this->paymentStatus = $this->paymentStatus->nowReadyToPay($paymentUrl['paymentUrl'], $paymentExpiryAt);
    }

    public function rejectPayByUser(\DateTime $dateTime)
    {
        $this->couponStatus=$this->couponStatus->nowRejectedPayByUser($dateTime);
        //@todo event
    }

    public function rejectBySecurityError(\DateTime $dateTime)
    {
        $this->couponStatus=$this->couponStatus->nowRejectedBySecurityError($dateTime);
        //@todo event
    }

    public function rejectByInvalidData(\DateTime $dateTime)
    {
        $this->couponStatus=$this->couponStatus->nowRejectedByInvalidData($dateTime);
        //@todo event
    }

    public function rejectByUserTimeout(\DateTime $dateTime)
    {
        $this->couponStatus=$this->couponStatus->nowRejectedByUserTimeout($dateTime);
        //@todo event
    }

    public function markAsPayStarted()
    {
        $this->paymentStatus=$this->paymentStatus->nowPayStarted();
    }

    public function getAggregateId()
    {
        return (string)$this->getId();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @return PersonalData
     */
    public function getPersonalData(): PersonalData
    {
        return $this->personalData;
    }

    /**
     * @return PaymentStatus
     */
    public function getPaymentStatus(): PaymentStatus
    {
        return $this->paymentStatus;
    }

    /**
     * @return CouponOrderStatus
     */
    public function getCouponOrderStatus(): CouponOrderStatus
    {
        return $this->couponStatus;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    public function getCreatedAt() : ?\DateTime
    {
        return $this->getCouponOrderStatus()->getCreatedAt();
    }
    private function generateHash(){
        $hashLong = md5(time().$this->getPersonalData()->getEmail()->getEmail().$this->getPrice()->getPrice());
        return substr($hashLong, 0, 10);
    }



}