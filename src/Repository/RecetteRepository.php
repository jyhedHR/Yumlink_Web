<?php

namespace App\Repository;

use App\Entity\Recette; // Corrected import to use Recette entity
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recette>
 *
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class); // Corrected parent::__construct() call to use Recette::class
    }

    /**
     * Fetches recipes with calorie counts under a certain threshold
     * 
     * @param int $calorieThreshold
     * @return Recette[]
     */
    public function getRecettesUnderCalorieThreshold(int $calorieThreshold): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.calorie < :calorieThreshold')
            ->setParameter('calorieThreshold', $calorieThreshold)
            ->getQuery()
            ->getResult();
    }

    public function deleteRecommendationsForUser($userId): void
    {
        $qb = $this->createQueryBuilder('r');
        $qb->delete()
        ->where('r.user.idu = :userId') 
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute();
    }

    public function insertRecommendations($recommendations, $userId): void
    {
        $conn = $this->getEntityManager()->getConnection();

        // Delete existing recommendations for this user
        $conn->executeStatement('DELETE FROM nutrition_recommandation WHERE user_id = :userId', ['userId' => $userId]);

        // Insert new recommendations
        $sql = 'INSERT INTO nutrition_recommandation (user_id, name) VALUES (:userId, :name)';
        $stmt = $conn->prepare($sql);

        foreach ($recommendations as $recommendation) {
            $stmt->execute(['userId' => $userId, 'name' => $recommendation->getNom()]);
        }
    }
}
