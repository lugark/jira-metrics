<?php

namespace App\JiraStatistics\Mapper\MySQL;

use App\Entity\Sprint;
use App\Entity\SprintIssueTypeStatistic;
use App\Entity\SprintTypeStatistic;
use App\JiraStatistics\IssueStatisticsInterface;
use App\JiraStatistics\Mapper\MapperInterface;
use App\Repository\SprintRepository;

class SprintStatisticsMySQL implements MapperInterface, MySQLMapperInterface
{
    use MySQLDBMapperTrait;

    public function __construct(private readonly SprintRepository $sprintRepository)
    {
    }

    public function mapStatistics(IssueStatisticsInterface $issueStatistics): array
    {
        $sprint = $this->sprintRepository->find($issueStatistics->getSprintId());
        if (empty($sprint)) {
            $sprint = new Sprint();
            $sprint->setId($issueStatistics->getSprintId())
                ->setGoal($issueStatistics->getSprintGoal())
                ->setStartDate($issueStatistics->getSprintStart())
                ->setTitle($issueStatistics->getSprintName());
        }

        $totalCount = $this->setSprintIssueTypeStatistics($sprint, $issueStatistics->getIssueCountsByType());
        $sprint->setIssueCount($totalCount);

        return [$sprint];
    }

    private function setSprintIssueTypeStatistics(Sprint $sprint, array $jiraTypeStats)
    {
        $totalIssueCount = 0;
        //update existing stats
        foreach ($sprint->getSprintIssueTypeStatistics() as $stat) {
            $issueType = $stat->getIssueType();
            $totalIssueCount += $jiraTypeStats[$issueType];
            if (array_key_exists($issueType, $jiraTypeStats)) {
                $stat->setCount($jiraTypeStats[$issueType]);
                unset($jiraTypeStats[$issueType]);
            }
        }

        //add those not existing
        foreach ($jiraTypeStats as $type => $count) {
            $typeStat = new SprintIssueTypeStatistic();
            $typeStat->setSprint($sprint)
                ->setIssueType($type)
                ->setCount($count);
            $sprint->addSprintIssueTypeStatistic($typeStat);
            $totalIssueCount += $count;
        }

        return $totalIssueCount;
    }
}
