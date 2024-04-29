<?php

namespace App\Repository;

use App\Entity\Recettes;
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
        parent::__construct($registry, Recettes::class);
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

//    public function findOneBySomeField($value): ?Recettes
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
