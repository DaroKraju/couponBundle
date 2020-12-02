<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order;


use IturDev\Domain\ValueObject;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Embeddable()
 */
class PaymentStatus extends ValueObject
{

    const READY_TO_PAY = "READY_TO_PAY";

    const START_PAY = "START_PAY";

    /**
     * @var string
     * @ORM\Column(type="string",  nullable=true)
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(type="string",  nullable=true)
     */
    private $url;

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
     * PaymentStatus constructor.
     * @param string $status
     * @param string $url
     * @param \DateTime $paidAt
     * @param \DateTime $expiryAt
     */
    public function __construct(?string $status=null, ?string $url=null, ?\DateTime $expiryAt=null, ?\DateTime $paidAt=null)
    {
        $this->status = $status;
        $this->url = $url;
        $this->paidAt = $paidAt;
        $this->expiryAt = $expiryAt;
    }

    public function blank(): self
    {
        return new self();
    }

    public function nowPayStarted()
    {
        return new self(self::START_PAY, $this->getUrl(), $this->getExpiryAt(), $this->getPaidAt());
    }


    public function nowReadyToPay(string $paymentUrl, \DateTime $expiryAt)
    {
        return new self(self::READY_TO_PAY, $paymentUrl, $expiryAt);
    }


    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return \DateTime
     */
    public function getPaidAt(): \DateTime
    {
        return $this->paidAt;
    }

    /**
     * @return \DateTime
     */
    public function getExpiryAt(): \DateTime
    {
        return $this->expiryAt;
    }


}