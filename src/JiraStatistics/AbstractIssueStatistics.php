<?php

namespace App\JiraStatistics;

abstract class AbstractIssueStatistics implements IssueStatisticsInterface
{
    /** @var IssueStatistic[] */
    private $issueData;

    private $boardColumnMapping;

    public function __construct(array $boardColumnMapping=[], array $issueData=[])
    {
        $this->boardColumnMapping = $this->flipBoardColumnMapping($boardColumnMapping);
        $this->issueData = $issueData;
    }

    private function flipBoardColumnMapping(array $boardColumnMapping)
    {
        $fliped = [];
        foreach ($boardColumnMapping as $name => $ids) {
            foreach ($ids as $id) {
                $fliped[$id] = $name;
            }
        }
        return $fliped;
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

    public function getCountsByBoardColumns(): array
    {
        $counts = [];
        foreach (array_unique($this->boardColumnMapping) as $column) {
            $counts[$column] = 0;
        }

        foreach ($this->issueData as $statistic) {
            $counts[$this->boardColumnMapping[$statistic->statusId]]++;
        }

        return $counts;
    }
}
