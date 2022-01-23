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

}
