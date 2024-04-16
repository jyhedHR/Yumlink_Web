<?php

namespace App\Repository;

use App\Entity\Defis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Defis>
 *
 * @method Defis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Defis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Defis[]    findAll()
 * @method Defis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DefisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Defis::class);
    }

//    /**
//     * @return Defis[] Returns an array of Defis objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Defis
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
