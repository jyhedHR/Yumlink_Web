<?php

namespace App\Repository;

use App\Entity\FileTransformer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FileTransformer>
 *
 * @method FileTransformer|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileTransformer|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileTransformer[]    findAll()
 * @method FileTransformer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileTransformerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileTransformer::class);
    }

//    /**
//     * @return FileTransformer[] Returns an array of FileTransformer objects
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

//    public function findOneBySomeField($value): ?FileTransformer
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
