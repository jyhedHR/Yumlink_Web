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
public function saveOrUpdate(Defis $defis)
{
    $now = new \DateTime();
    if ($defis->getDelai() < $now) {
        throw new \Exception('The challenge date has already expired.');
        // or you can return an error message instead of throwing an exception
    }

    $this->_em->persist($defis);
    $this->_em->flush();
}
public function findByNom(string $nom): array
{
    return $this->createQueryBuilder('d')
        ->andWhere('d.nomD LIKE :nom')
        ->setParameter('nom', '%'.$nom.'%')
        ->getQuery()
        ->getResult();
}
public function findActiveDefis(): array
{
    return $this->createQueryBuilder('d')
        ->andWhere('d.delai >= :now')
        ->setParameter('now', new \DateTime())
        ->orderBy('d.delai', 'ASC')
        ->addOrderBy('d.heure', 'ASC')
        ->getQuery()
        ->getResult();
}
public function findExpiredDefis(): array
{
    return $this->createQueryBuilder('d')
        ->andWhere('d.delai < :now OR (d.delai = :now AND d.heure < :heure)')
        ->setParameter('now', new \DateTime())
        ->setParameter('heure', new \DateTime())
        ->orderBy('d.delai', 'DESC')
        ->addOrderBy('d.heure', 'DESC')
        ->getQuery()
        ->getResult();
}
}
