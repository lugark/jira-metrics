<?php

namespace App\JiraStatistics;

use App\Jira\Board\Configuration;
use JiraRestApi\Sprint\Sprint;

class BoardStatistics extends AbstractIssueStatistics
{
    public function __construct(protected Configuration $boardConfig, array $boardColumnMapping=[], array $issueData=[])
    {
        parent::__construct($boardColumnMapping, $issueData);
    }

    public function getIssueGroupName(): string
    {
        return $this->boardConfig->name;
    }

    public function getIssueGroupId(): int
    {
        return $this->boardConfig->id;
    }
}
