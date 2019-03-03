<?php

namespace App\Repository;

use App\Entity\Conge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Conge|null find($idConge, $lockMode = null, $lockVersion = null)
 * @method Conge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conge[]    findAll()
 * @method Conge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CongeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Conge::class);
    }

    // /**
    //  * @return Conge[] Returns an array of Conge objects for self
    //  */
    public function findByCollaborateurId($idCollabo)
    {
      return $this->createQueryBuilder('c')
          ->andWhere('c.collabo = :val')
          ->setParameter('val', $idCollabo)
          ->addOrderBy('c.date_debut', 'DESC')
          ->setMaxResults(15)
          ->getQuery()
          ->getResult()
      ;
    }
    // /**
    //  * @return Conge[] Returns an array of Conge objects for entire service
    //  */
    public function findByServiceId($idService)
    {
      return $this->createQueryBuilder('c')
          ->andWhere('c.service = :val')
          ->setParameter('val', $idService)
          ->addOrderBy('c.date_debut', 'DESC')
          ->setMaxResults(15)
          ->getQuery()
          ->getResult()
      ;
    }


    public function findByServiceAndStatut($serviceId,$Statut){
        return $this->createQueryBuilder('c')
            ->andWhere('c.service = :val')
            ->andWhere('c.statut = :val2')
            ->setParameter('val', $serviceId)
            ->setParameter('val2', $Statut)
            ->addOrderBy('c.date_debut', 'DESC')
            ->setMaxResults(15)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findOneById($id): ?Conge
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id_conge = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
