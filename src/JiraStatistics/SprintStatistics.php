<?php

namespace App\JiraStatistics;

use JiraRestApi\Sprint\Sprint;

class SprintStatistics
{
    /** @var IssueStatistic[] */
    private $issueData;

    /** @var Sprint */
    private $sprint;

    public function __construct(Sprint $sprint, array $issueData=[])
    {
        $this->sprint = $sprint;
        $this->issueData = $issueData;
    }

    public function addIssueStatistic(IssueStatistic $issueStatistic)
    {
        $this->issueData[] = $issueStatistic;
    }

    public function getIssueCountsByType(): array
    {
        $counts = [];
        foreach ($this->issueData as $statistic) {
            $counts[$statistic->issueType] = array_key_exists($statistic->issueType, $counts)
                ? $counts[$statistic->issueType] + 1
                : 1;
        }
        return $counts;
    }

    public function getIssueCountsByState(): array
    {
        $counts = [];
        foreach ($this->issueData as $statistic) {
            $counts[$statistic->status] = array_key_exists($statistic->status, $counts)
                ? $counts[$statistic->status] + 1
                : 1;
        }
        return $counts;
    }

    public function getIssueCountByTypeAndState(): array
    {
        $counts = [];
        foreach ($this->issueData as $statistic) {
            $status = $statistic->status;
            $type = $statistic->issueType;
            if (!array_key_exists($type, $counts)) {
                $counts[$type] = [];
            }
            if (!array_key_exists($status, $counts[$type])) {
                $counts[$type][$status] = 0;
            }

            $counts[$type][$status] ++;
        }
        return $counts;
    }

    public function getSprintName(): string
    {
        return $this->sprint->getName();
    }

    public function getSprintStart(): \DateTime
    {
        return new \DateTime($this->sprint->startDate);
    }

    public function getSprintGoal(): string
    {
        return $this->sprint->goal;
    }
}
