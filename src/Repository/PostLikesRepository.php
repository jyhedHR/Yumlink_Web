<?php

namespace App\Repository;

use App\Entity\PostLikes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostLikes>
 *
 * @method PostLikes|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostLikes|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostLikes[]    findAll()
 * @method PostLikes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostLikesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostLikes::class);
    }

//    /**
//     * @return PostLikes[] Returns an array of PostLikes objects
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

//    public function findOneBySomeField($value): ?PostLikes
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
