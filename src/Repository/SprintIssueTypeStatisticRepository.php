<?php

namespace App\Repository;

use App\Entity\SprintIssueTypeStatistic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SprintIssueTypeStatistic|null find($id, $lockMode = null, $lockVersion = null)
 * @method SprintIssueTypeStatistic|null findOneBy(array $criteria, array $orderBy = null)
 * @method SprintIssueTypeStatistic[]    findAll()
 * @method SprintIssueTypeStatistic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SprintIssueTypeStatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SprintIssueTypeStatistic::class);
    }

    // /**
    //  * @return SprintIssueTypeStatistic[] Returns an array of SprintIssueTypeStatistic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SprintIssueTypeStatistic
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
