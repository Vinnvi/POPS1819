<?php

namespace App\Repository;

use App\Entity\LigneDeFrais;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LigneDeFrais|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneDeFrais|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneDeFrais[]    findAll()
 * @method LigneDeFrais[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneDeFraisRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LigneDeFrais::class);
    }

    // /**
    //  * @return LigneDeFrais[] Returns an array of LigneDeFrais objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LigneDeFrais
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
