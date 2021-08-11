<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findCategoryByUrl($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.url = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

/*
    public function findAllCategoryToProduct(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 
        '
        SELECT product.name, category.name
        FROM category
        INNER JOIN product_category 
        ON category.id = product_category.category_id
        INNER JOIN product
        ON product.id = product_category.product_id
        '
        ;
        $stmt = $conn->prepare($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }
    */
    public function findAllCategories(): array
    {
        return $this->createQueryBuilder('c')
        ->orderBy('c.level', 'ASC')
        //->setMaxResults(10)
        ->getQuery()
        ->getResult();
    }
    public function findAllCategoriesWithProducts(): array
    {
        return $this->createQueryBuilder('c')
        ->addSelect('p')
        ->join('c.products','p')
        ->orderBy('c.level', 'ASC')
        //->setMaxResults(10)
        ->getQuery()
        ->getResult();
    }
}
