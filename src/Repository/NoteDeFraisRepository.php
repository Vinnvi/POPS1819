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
            ->addOrderBy('n.annee', 'DESC')
            ->addOrderBy('n.mois', 'DESC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByStatus($status)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.statut = :val')
            ->setParameter('val',$status)
            ->addOrderBy('n.lastModif')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;

    }

    public function findByStatusAndCollabo($status,$collaboId)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.statut = :val')
            ->andWhere('n.collabo = :val2')
            ->setParameter('val',$status)
            ->setParameter('val2',$collaboId)
            ->addOrderBy('n.lastModif')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
            ;

    }

    public function findByMonthAndYear($month,$year,$collaboId)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.mois = :val')
            ->andWhere('n.annee = :val2')
            ->andWhere('n.collabo = :val3')
            ->setParameter('val', $month)
            ->setParameter('val2', $year)
            ->setParameter('val3', $collaboId)
            ->orderBy('n.mois,n.annee', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findEnAttente()
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.statut = :val')
            ->setParameter('val', "En attente")
            ->orderBy('n.id')
            ->getQuery()
            ->getResult()
        ;
    }



    public function findOneByID($id): ?NoteDeFrais
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
