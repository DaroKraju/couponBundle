<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Infrastructure\Persistence\Doctrine;


use Doctrine\ORM\EntityManagerInterface;
use IturDev\Domain\AggregateRoot;
use IturDev\Domain\DomainDoctrineORMRepository;
use IturDev\Domain\Identity\IdentityGenerator;
use Noclegowo\Editpanel\Core\Module\Coupon\Domain\CouponOrderRepository;
use Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order\CouponOrder;

class CouponOrderDoctrineRepository extends DomainDoctrineORMRepository implements CouponOrderRepository
{
    const ENTITY_NAME = "CoreCouponDomainOrder:CouponOrder";

    /**
     * CouponOrderDoctrineRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, IdentityGenerator $identityGenerator)
    {
        parent::__construct($entityManager, $identityGenerator);

    }


    public function findById($id) : ?CouponOrder
    {
        $q = $this->getQueryBuilder()
            ->select('c')
            ->from(self::ENTITY_NAME, 'c')
            ->where("c.id = :identity" )
            ->setParameter("identity", $id);

        return $q->getQuery()->getOneOrNullResult();
    }

    public function findByHash($hash) : ?CouponOrder
    {
        $q = $this->getQueryBuilder()
            ->select('c')
            ->from(self::ENTITY_NAME, 'c')
            ->where("c.hash = :hash" )
            ->setParameter("hash", $hash);

        return $q->getQuery()->getOneOrNullResult();
    }

    public function save(AggregateRoot $couponOrder)
    {
        $this->entityManager->persist($couponOrder);
        $this->entityManager->flush();
    }


    public function getEntityClass()
    {
        return self::ENTITY_NAME;
    }

}