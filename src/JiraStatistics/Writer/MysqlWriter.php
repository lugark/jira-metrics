<?php

namespace App\JiraStatistics\Writer;

use App\JiraStatistics\Mapper\MapperAwareInterface;
use App\JiraStatistics\Mapper\MapperAwareTrait;
use App\JiraStatistics\SprintStatistics;
use Doctrine\ORM\EntityManagerInterface;

class MysqlWriter implements WriterInterface, MapperAwareInterface
{
    use MapperAwareTrait;

    /** @var EntityManagerInterface */
    public $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function writeData(SprintStatistics $statistics)
    {
        foreach ($this->mapper as $mapper) {
            $entities = $mapper->mapStatistics($statistics);
            foreach ($entities as $entity) {
                $this->entityManager->persist($entity);
            }
        }
        $this->entityManager->flush();
    }
}