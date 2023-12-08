<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 *
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @return Review[] Returns an array of Review objects
     */
    public function findByFilmField($value): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.film = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByIDField($value): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
            ;
    }

    public function averageFilmScore($value): int
    {
        $all = $this->createQueryBuilder('a')
            ->andWhere('a.film = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
        $total = 0;
        $number = 0;
        foreach ($all as $r)
        {
            $total += $r->getRating();
            $number += 1;
        }
        return $total / $number;
    }

    public function findByTwoField($value1, $value2): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.reviewer = :val1')
            ->andWhere('r.reviewText = :val2')
            ->setParameter('val1', $value1)
            ->setParameter('val2', $value2)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
            ;
    }
}
