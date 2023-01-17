<?php

namespace App\JiraStatistics;

use App\Jira\Board\Configuration;
use JiraRestApi\Sprint\Sprint;

class BoardStatistics extends AbstractStatistics
{
    protected Configuration $boardConfig;

    public function __construct(Configuration $boardConfig, array $boardColumnMapping=[], array $issueData=[])
    {
        $this->boardConfig = $boardConfig;
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
