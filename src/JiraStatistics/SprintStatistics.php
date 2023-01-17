<?php

namespace App\JiraStatistics;

use JiraRestApi\Sprint\Sprint;

class SprintStatistics extends AbstractIssueStatistics
{
    public function __construct(private readonly Sprint $sprint, array $boardColumnMapping=[], array $issueData=[])
    {
        parent::__construct($boardColumnMapping, $issueData);
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

    public function getSprintId(): int
    {
        return $this->sprint->id;
    }

    public function getIssueGroupName(): string
    {
        return $this->getSprintName();
    }

    public function getIssueGroupId(): int
    {
        return $this->getSprintId();
    }

}
