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

    public function findByNoteID($NoteId)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.Note = :val')
            ->setParameter('val', $NoteId)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return LigneDeFrais Returns element of LigneDeFrais objects
    //  */

    public function findOneByID($Id): ?LigneDeFrais
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.id = :val')
            ->setParameter('val', $Id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByCollaborateurID($id)
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('l.Note', 'n')
            ->where('n.collabo = :collabo_id')
            ->setParameter('collabo_id', $id)
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();

    }


    public function findLignesChef($projet)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.Projet = :val')
            ->andWhere('l.statutValidation = :val2')
            ->setParameter('val', $projet)
            ->setParameter('val2', LigneDeFrais::STATUS[1])
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();


    }
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
