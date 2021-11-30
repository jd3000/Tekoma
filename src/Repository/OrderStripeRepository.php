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

    /**
     * @return OrderStripe[] Returns an array of OrderStripe objects
     */
    public function findByUserName($userName): array
    {
        $qb = $this->createQueryBuilder('o')
            ->select('o')
            ->where('o.username = :username')
            ->setParameter('username', $userName)
            ->orderBy('o.username', 'ASC');
        $query = $qb->getQuery();
        return $query->execute();
    }



    public function findOneByReference($value): ?OrderStripe
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.reference = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
