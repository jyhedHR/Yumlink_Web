<?php

namespace App\Repository;

use App\Entity\RecettesIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recettesingredient>
 *
 * @method Recettesingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recettesingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recettesingredient[]    findAll()
 * @method Recettesingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecettesingredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recettesingredient::class);
    }

//    /**
//     * @return Recettesingredient[] Returns an array of Recettesingredient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recettesingredient
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
