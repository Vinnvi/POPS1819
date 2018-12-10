<?php

namespace App\Repository;

use App\Entity\NoteDeFrais;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NoteDeFrais|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoteDeFrais|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoteDeFrais[]    findAll()
 * @method NoteDeFrais[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteDeFraisRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NoteDeFrais::class);
    }

    // /**
    //  * @return NoteDeFrais[] Returns an array of NoteDeFrais objects
    //  */

    public function findByCollaborateurId($id)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.collabo = :val')
            ->setParameter('val', $id)
            ->orderBy('n.mois,n.annee', 'ASC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?NoteDeFrais
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
