<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findTopArticles(int $limit): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.nbLikesArticle', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByTitleOrder($order = 'ASC')
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.titleArticle', $order)
            ->getQuery()
            ->getResult();
    }

    /**
     * Fetch articles by user.
     *
     * @param User $user
     * @return array
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * Fetch articles by user.
     *
     * @param User $user
     * @return Query
     */
    public function findByUserQuery(User $user): Query
    {
        return $this->createQueryBuilder('a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->getQuery();
    }

    /**
     * Fetch all articles.
     *
     * @return Query
     */
    public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('a')->getQuery();
    }

    /**
     * Fetch by tag using MEMBER OF
     * 
     * @param string $tag
     * @return Query
     */
    public function findByTag(string $tag): Query
    {
        return $this->createQueryBuilder('a')
            ->where(':tag MEMBER OF a.tags')
            ->setParameter('tag', $tag)
            ->getQuery();
    }

    //    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
