<?php

namespace App\Repository;

use App\Entity\UserNutrition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserNutrition>
 *
 * @method UserNutrition|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserNutrition|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserNutrition[]    findAll()
 * @method UserNutrition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserNutritionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserNutrition::class);
    }



//    /**
//     * @return UserNutrition[] Returns an array of UserNutrition objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserNutrition
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
