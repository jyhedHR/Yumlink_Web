<?php

namespace App\Repository;

use App\Entity\Reprandre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reprandre>
 *
 * @method Reprandre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reprandre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reprandre[]    findAll()
 * @method Reprandre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReprandreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reprandre::class);
    }

//    /**
//     * @return Reprandre[] Returns an array of Reprandre objects
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

//    public function findOneBySomeField($value): ?Reprandre
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
