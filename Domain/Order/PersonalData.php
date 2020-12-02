<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order;

use IturDev\Domain\Identity\Email;
use IturDev\Domain\ValueObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class PersonalData extends ValueObject
{

    /**
     * @var Email
     * @ORM\Embedded(class="IturDev\Domain\Identity\Email", columnPrefix=false)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $userProfileId;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $sessionUserId;

    /**
     * PersonalData constructor.
     * @param Email $email
     * @param string $userProfileId
     * @param string $sessionUserId
     */
    public function __construct(Email $email, string $userProfileId, string $sessionUserId)
    {
        $this->email = $email;
        $this->userProfileId = $userProfileId;
        $this->sessionUserId = $sessionUserId;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getUserProfileId(): ?string
    {
        return $this->userProfileId;
    }

    /**
     * @return string
     */
    public function getSessionUserId(): ?string
    {
        return $this->sessionUserId;
    }



}