<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order;


use IturDev\Domain\ValueObject;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Embeddable()
 */
class CouponOrderStatus extends ValueObject
{
    const NEW = "NEW";

    const REJECTED_PAY_BY_USER = "REJECTED_PAY_BY_USER";

    const REJECTED_BY_SECURITY_ERROR = "REJECTED_BY_SECURITY_ERROR";

    const REJECTED_BY_INVALID_TRANSACTION_DATA  = "REJECTED_BY_INVALID_TRANSACTION_DATA";

    const REJECTED_BY_USER_TIMEOUT  = "REJECTED_BY_USER_TIMEOUT";

    const PAID = "PAID";

    const EXPIRED = "EXPIRED";

    const USED = "USED";


    /**
     * @var string
     * @ORM\Column(type="string",  nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime",  nullable=true)
     */
    private $rejectedAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime",  nullable=true)
     */
    private $paidAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime",  nullable=true)
     */
    private $expiryAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="boolean",  nullable=true)
     *
     */
    private $used_at;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime",  nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * CouponOrderStatus constructor.
     * @param string $status
     * @param \DateTime $rejectedAt
     * @param \DateTime $paidAt
     * @param \DateTime $expiryAt
     * @param \DateTime $createdAt
     */
    public function __construct(string $status, ?\DateTime $createdAt = null, ?\DateTime $rejectedAt = null, ?\DateTime $paidAt = null, ?\DateTime $expiryAt = null,  ?\DateTime $usedAt = null)
    {
        $this->status = $status;
        $this->rejectedAt = $rejectedAt;
        $this->paidAt = $paidAt;
        $this->expiryAt = $expiryAt;
        $this->used_at = $usedAt;
        $this->createdAt=$createdAt;

    }

    public static function blank(): self
    {
        return new self();
    }

    public static function newWithDefaultExpiry(\DateTime $dateTime): self
    {
        $expiryAt = new \DateTime( $dateTime->format("Y-m-d H:i:s") .' +1 year');
        return new self(self::NEW, $dateTime, null, null, $expiryAt, null);
    }

    public static function new(\DateTime $dateTime): self
    {
        return new self(self::NEW, $dateTime);
    }

    public function nowRejectedPayByUser(\DateTime $dateTime): self
    {
        return new self(self::REJECTED_PAY_BY_USER, $this->getCreatedAt(), $dateTime, $this->getPaidAt(), $this->getExpiryAt(), $this->getUsedAt());
    }

    public function nowRejectedBySecurityError(\DateTime $dateTime): self
    {
        return new self(self::REJECTED_BY_SECURITY_ERROR, $this->getCreatedAt(), $dateTime, $this->getPaidAt(), $this->getExpiryAt(), $this->getUsedAt());
    }

    public function nowRejectedByUserTimeout(\DateTime $dateTime): self
    {
        return new self(self::REJECTED_BY_USER_TIMEOUT, $this->getCreatedAt(), $dateTime, $this->getPaidAt(), $this->getExpiryAt(), $this->getUsedAt());
    }

    public function nowRejectedByInvalidData(\DateTime $dateTime): self
    {
        return new self(self::REJECTED_BY_INVALID_TRANSACTION_DATA, $this->getCreatedAt(), $dateTime, $this->getPaidAt(), $this->getExpiryAt(), $this->getUsedAt());

    }
    public function nowPaid(\DateTime $dateTime): self
    {
        return new self(self::PAID, $this->getCreatedAt(), $this->getRejectedAt(), $dateTime, $this->getExpiryAt(), $this->getUsedAt());
    }

    public function isNew() {
        return $this->status==self::NEW;
    }

    public function isPaid() {
        return $this->status==self::PAID;
    }

    public function isExpired() {
        return $this->status==self::EXPIRED;
    }
    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return \DateTime
     */
    public function getRejectedAt(): ?\DateTime
    {
        return $this->rejectedAt;
    }

    /**
     * @return \DateTime
     */
    public function getPaidAt(): ?\DateTime
    {
        return $this->paidAt;
    }

    /**
     * @return \DateTime
     */
    public function getExpiryAt(): ?\DateTime
    {
        return $this->expiryAt;
    }

    /**
     * @return \DateTime
     */
    public function getUsedAt(): ?\DateTime
    {
        return $this->used_at;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

}