<?php

namespace App\Repository;

use App\Entity\BackgroundPicPath;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BackgroundPicPath|null find($id, $lockMode = null, $lockVersion = null)
 * @method BackgroundPicPath|null findOneBy(array $criteria, array $orderBy = null)
 * @method BackgroundPicPath[]    findAll()
 * @method BackgroundPicPath[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackgroundPicPathRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BackgroundPicPath::class);
    }

    // /**
    //  * @return BackgroundPicPath[] Returns an array of BackgroundPicPath objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BackgroundPicPath
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
