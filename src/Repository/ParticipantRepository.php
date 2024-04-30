<?php

namespace App\Repository;

use App\Entity\Participant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Participant>
 *
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }
      /**
     * Find participants related to a specific defis.
     *
     * @param int $defisId The ID of the defis
     * @return Participant[] Returns an array of Participant objects
     */
    public function findByDefis(int $defisId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.defis = :defisId')
            ->setParameter('defisId', $defisId)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Participant[] Returns an array of Participant objects
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

//    public function findOneBySomeField($value): ?Participant
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

   /**
     * Get the participation counts per challenge.
     *
     * @return array
     */
    public function getParticipationCountsPerChallenge(): array
    {
        return $this->createQueryBuilder('p')
            ->select('IDENTITY(p.defis) AS challengeId, COUNT(p) AS participantCount')
            ->groupBy('p.defis')
            ->getQuery()
            ->getResult();
    }

}
