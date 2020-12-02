<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Query\CouponOrder;


use Doctrine\ORM\QueryBuilder;
use IturDev\Query\DoctrineORMQuery;
use Noclegowo\Editpanel\Core\Module\Coupon\Domain\CouponOrderRepository;
use Noclegowo\Editpanel\Core\Module\Coupon\Infrastructure\Persistence\Doctrine\CouponOrderDoctrineRepository;
use Noclegowo\Editpanel\Core\Module\Coupon\Query\CouponOrder\Result\CouponOrderPreview;
use IturDev\Query\FormatterRegistry;


class CouponOrderQuery extends DoctrineORMQuery
{


    public function __construct(CouponOrderDoctrineRepository $couponOrderRepository, FormatterRegistry $formatterRegistry)
    {
        parent::__construct($couponOrderRepository, $formatterRegistry);
    }

    public function getName()
    {
      return "CouponOrder";
    }


    public function filterById(QueryBuilder $queryBuilder, $parameter)
    {
        $queryBuilder->andWhere("entity.id = :identity")
            ->setParameter("identity", $parameter);
    }

    public function filterByEmail(QueryBuilder $queryBuilder, $parameter)
    {
        $queryBuilder->andWhere("entity.personalData.email.email = :email")
            ->setParameter("email", $parameter);
    }

    public function filterByPrice(QueryBuilder $queryBuilder, $parameter)
    {
        $queryBuilder->andWhere("entity.price.price = :price")
            ->setParameter("price", $parameter);
    }

    public function filterByHash(QueryBuilder $queryBuilder, $parameter)
    {
        $queryBuilder->andWhere("entity.hash = :hash")
            ->setParameter("hash", $parameter);
    }

    protected function sortRecent(QueryBuilder $queryBuilder)
    {
        $queryBuilder
            //->orderBy("entity.cocreatedAt", "DESC")
            ->orderBy("entity.id", "DESC");
    }


    protected function getFormatters()
    {
       return [
           "preview" => CouponOrderPreview::class
       ];
    }


}