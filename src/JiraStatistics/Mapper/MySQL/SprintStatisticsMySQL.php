<?php

namespace App\JiraStatistics\Mapper\MySQL;

use App\Entity\Sprint;
use App\Entity\SprintIssueTypeStatistic;
use App\Entity\SprintTypeStatistic;
use App\JiraStatistics\Mapper\MapperInterface;
use \App\JiraStatistics\SprintStatistics;
use App\Repository\SprintRepository;

class SprintStatisticsMySQL implements MapperInterface, MySQLMapperInterface
{
    use MySQLDBMapperTrait;

    /** @var SprintRepository */
    private $sprintRepository;

    public function __construct(SprintRepository $sprintRepository)
    {
        $this->sprintRepository = $sprintRepository;
    }

    public function mapStatistics(SprintStatistics $sprintStatistics): array
    {
        $sprint = $this->sprintRepository->find($sprintStatistics->getSprintId());
        if (empty($sprint)) {
            $sprint = new Sprint();
            $sprint->setId($sprintStatistics->getSprintId())
                ->setGoal($sprintStatistics->getSprintGoal())
                ->setStartDate($sprintStatistics->getSprintStart())
                ->setTitle($sprintStatistics->getSprintName());
        }

        $totalCount = $this->setSprintIssueTypeStatistics($sprint, $sprintStatistics->getIssueCountsByType());
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