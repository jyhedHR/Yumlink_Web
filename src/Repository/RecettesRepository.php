<?php

namespace App\Repository;

use App\Entity\Recettes; // Corrected import to use Recettes entity
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recettes>
 *
 * @method Recettes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recettes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recettes[]    findAll()
 * @method Recettes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecettesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recettes::class); // Corrected parent::__construct() call to use Recettes::class
    }

    /**
     * Fetches recipes with calorie counts under a certain threshold
     * 
     * @param int $calorieThreshold
     * @return Recettes[]
     */
    public function getRecettesUnderCalorieThreshold(int $calorieThreshold): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.nom', 'r.calorie') // Selecting name and calorie attributes
            ->andWhere('r.calorie < :calorieThreshold')
            ->setParameter('calorieThreshold', $calorieThreshold)
            ->getQuery()
            ->getResult();
    }

    public function searchByNameOrChef($query)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.nom LIKE :query OR r.chef LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }
    public function getRecipesPerCategory()
    {
        return $this->createQueryBuilder('r')
            ->select('r.categorie as category, COUNT(r) as recipe_count')
            ->groupBy('r.categorie')
            ->getQuery()
            ->getResult();
    }

    public function countRecipesByMonth()
    {
        $query = $this->createQueryBuilder('r')
            ->select('r.date AS date')
            ->getQuery();

        $results = $query->getResult();

        $activityData = [];
        foreach ($results as $result) {
            $month = $result['date']->format('n'); // Extract month (1-12)
            if (!isset($activityData[$month])) {
                $activityData[$month] = 0;
            }
            $activityData[$month]++;
        }

        return $activityData;
    }
    public function getMostPopularChefs(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.chef AS chef_name, COUNT(r.idR) AS recipe_count')
            ->groupBy('r.chef')
            ->orderBy('recipe_count', 'DESC')
            ->setMaxResults(5) // Optional: Limit to the top 5 chefs
            ->getQuery()
            ->getResult();
    }
    public function findByCaloriesAndProtein($calorie, $protein): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.calorie = :calorie')
            ->setParameter('calorie', $calorie)
            ->andWhere('r.protein = :protein')
            ->setParameter('protein', $protein)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Recettes[] Returns an array of Recettes objects
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

    // Concatenate all names into a single string
    $names = '';
    foreach ($recommendations as $recommendation) {
        $names .= $recommendation['nom'] . ', ';
    }
    $names = rtrim($names, ', '); // Remove trailing comma and space

    // Insert concatenated names into the nutrition_recommandation table
    $sql = 'INSERT INTO nutrition_recommandation (user_id, name) VALUES (:userId, :names)';
    $stmt = $conn->prepare($sql);
    $stmt->execute(['userId' => $userId, 'names' => $names]);
    }

    
}
