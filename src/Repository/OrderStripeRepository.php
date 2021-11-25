<?php

namespace App\Repository;

use App\Entity\OrderStripe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderStripe|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderStripe|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderStripe[]    findAll()
 * @method OrderStripe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderStripeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderStripe::class);
    }

    // /**
    //  * @return OrderStripe[] Returns an array of OrderStripe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderStripe
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
