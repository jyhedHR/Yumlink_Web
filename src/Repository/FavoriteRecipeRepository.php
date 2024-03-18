<?php

namespace App\Repository;

use App\Entity\FavoriteRecipes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FavoriteRecipes>
 *
 * @method FavoriteRecipes|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteRecipes|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteRecipes[]    findAll()
 * @method FavoriteRecipes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteRecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteRecipes::class);
    }

//    /**
//     * @return FavoriteRecipes[] Returns an array of FavoriteRecipes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FavoriteRecipes
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
