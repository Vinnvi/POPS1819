<?php

namespace App\Repository;

use App\Entity\Projet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Projet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projet[]    findAll()
 * @method Projet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Projet::class);
    }

    // /**
    //  * @return Projet[] Returns an array of Projet objects
    //  */

    public function findByService($serviceId)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.Service = :val')
            ->setParameter('val', $serviceId)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Projet Returns a Projet object
    //  */
    public function findById($id)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id= :val')
            ->setParameter('val', $id)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Projet[] Returns an array of Projet objects
    //  */

    public function findByCollaborateurId($id)
    {
        return $this->createQueryBuilder('p')
          ->innerJoin('p.collabos', 'c')
          ->where('c.id = :collabo_id')
          ->setParameter('collabo_id', $id)
          ->getQuery()->getResult();

    }

    // /**
    //  * @return Projet[] Returns an array of Projet objects
    //  */

    public function findAll()
    {
        return $this->createQueryBuilder('p')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Projet
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
