<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByFilters(?string $name, ?float $minPrice, ?float $maxPrice, ?string $sortBy = null, ?string $order = null): array
    {
        $qb = $this->createQueryBuilder('p');

        if ($name) {
            $qb->andWhere('p.name = :name')
            ->setParameter('name', $name);
        }

        if ($minPrice !== null) {
            $qb->andWhere('p.price >= :min')
            ->setParameter('min', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('p.price <= :max')
            ->setParameter('max', $maxPrice);
        }


        if (in_array($sortBy, ['price', 'name']) && in_array(strtolower($order), ['asc', 'desc'])) {
            $qb->orderBy('p.' . $sortBy, $order);
        }

        return $qb->getQuery()->getResult();
    }
    public function getFilteredQueryBuilder(?string $name, ?float $minPrice, ?float $maxPrice, ?string $sortBy = null, ?string $order = null)
    {
        $qb = $this->createQueryBuilder('p');

        if ($name) {
            $qb->andWhere('p.name = :name')
            ->setParameter('name', $name);
        }

        if ($minPrice !== null) {
            $qb->andWhere('p.price >= :minPrice')
            ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('p.price <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice);
        }

        if (in_array($sortBy, ['price', 'name']) && in_array(strtolower($order), ['asc', 'desc'])) {
            $qb->orderBy('p.' . $sortBy, $order);
        }

        return $qb;
    }



//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
