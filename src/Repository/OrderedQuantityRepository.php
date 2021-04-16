<?php

namespace App\Repository;

use App\Entity\OrderedQuantity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderedQuantity|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderedQuantity|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderedQuantity[]    findAll()
 * @method OrderedQuantity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderedQuantityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderedQuantity::class);
    }

    // /**
    //  * @return OrderedQuantity[] Returns an array of OrderedQuantity objects
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
    public function findOneBySomeField($value): ?OrderedQuantity
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
