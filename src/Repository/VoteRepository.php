<?php

namespace App\Repository;

use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vote>
 *
 * @method Vote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vote[]    findAll()
 * @method Vote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

//    /**
//     * @return Vote[] Returns an array of Vote objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Vote
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
  /**
     * Check if the user has already voted for a participant.
     *
     * @param int $userId The ID of the user
     * @param int $participantId The ID of the participant
     * @return bool True if the user has voted for the participant, false otherwise
     */
    public function hasUserVotedForParticipant(int $userId, int $participantId): bool
    {
        $queryBuilder = $this->createQueryBuilder('v')
            ->select('COUNT(v.idVote)')
            ->where('v.user = :userId')
            ->andWhere('v.participant = :participantId')
            ->setParameter('userId', $userId)
            ->setParameter('participantId', $participantId);

        $count = $queryBuilder->getQuery()->getSingleScalarResult();

        return $count > 0;
    }
}
