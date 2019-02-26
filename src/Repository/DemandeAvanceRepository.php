<?php

namespace App\Repository;

use App\Entity\DemandeAvance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DemandeAvance|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeAvance|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeAvance[]    findAll()
 * @method DemandeAvance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeAvanceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DemandeAvance::class);
    }

    // /**
    //  * @return DemandeAvance[] Returns an array of DemandeAvance objects
    //  */

    public function findByStatut($statut)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statut = :val')
            ->setParameter('val', $statut)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?DemandeAvance
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
