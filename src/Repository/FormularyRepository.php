<?php

namespace App\Repository;

use App\Entity\Formulary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formulary|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formulary|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formulary[]    findAll()
 * @method Formulary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormularyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formulary::class);
    }

    // /**
    //  * @return Formulary[] Returns an array of Formulary objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Formulary
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
