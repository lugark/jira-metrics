<?php

namespace App\JiraStatistics;

use App\JiraStatistics\Aggregator\AggregationItem;
use App\JiraStatistics\Aggregator\AggregationResult;
use App\JiraStatistics\Aggregator\AggregatorAwareInterface;
use App\JiraStatistics\Aggregator\AggregatorAwareTrait;
use App\JiraStatistics\Aggregator\IssueAggregator;
use App\JiraStatistics\Aggregator\IssueCountAggregator;

abstract class AbstractStatistics implements StatisticsInterface, AggregatorAwareInterface
{
    use AggregatorAwareTrait;

    /** @var IssueInformation[] */
    private $issueData = [];

    private $boardColumnMapping;

    public function __construct(array $boardColumnMapping=[], array $issueData=[])
    {
        $this->boardColumnMapping = $this->flipBoardColumnMapping($boardColumnMapping);
        $this->issueData = $issueData;
        $this->addAggregator(new IssueCountAggregator());
        $this->addAggregator(new IssueAggregator());
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

    public function addIssueInformation(IssueInformation $issueInformation)
    {
        $this->issueData[] = $issueInformation;
    }

    public function aggregate(string $aggregatorName, string $aggregation): AggregationResult
    {
        return $this->runAggregator($aggregatorName, $aggregation, $this->issueData);
    }

    /**
     * @deprecated
     * @codeCoverageIgnore
     */
    public function getIssueCountsByType(): array
    {
        $counts = [];
        foreach ($this->issueData as $statistic) {
            $counts[$statistic->getIssueType()] = array_key_exists($statistic->getIssueType(), $counts)
                ? $counts[$statistic->getIssueType()] + 1
                : 1;
        }
        return $counts;
    }

    /**
     * @deprecated
     * @codeCoverageIgnore
     */
    public function getIssueCountsByState(): array
    {
        $counts = [];
        foreach ($this->issueData as $statistic) {
            $counts[$statistic->getStatus()] = array_key_exists($statistic->getStatus(), $counts)
                ? $counts[$statistic->getStatus()] + 1
                : 1;
        }
        return $counts;
    }

    /**
     * @deprecated
     * @codeCoverageIgnore
     */
    public function getIssueCountByTypeAndState(): array
    {
        $counts = [];
        foreach ($this->issueData as $statistic) {
            $status = $statistic->getStatus();
            $type = $statistic->getIssueType();
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

    /**
     * @deprecated
     * @codeCoverageIgnore
     */
    public function getCountsByBoardColumns(): array
    {
        $counts = [];
        foreach (array_unique($this->boardColumnMapping) as $column) {
            $counts[$column] = 0;
        }

        foreach ($this->issueData as $statistic) {
            $counts[$this->boardColumnMapping[$statistic->getStatusId()]]++;
        }

        return $counts;
    }

    public function getIssueCountByColumn(): AggregationResult
    {
        $result = new AggregationResult();

        foreach ($this->issueData as $statistic) {
            $result->increase($this->boardColumnMapping[$statistic->getStatusId()]);
        }

        return $result;
    }
}
