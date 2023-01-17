<?php

namespace App\JiraStatistics;

class IssueStatistic
{
    /**
     * @param string $created
     * @param string $issueType
     * @param string $issueName
     * @param string $status
     * @param int $statusId
     */
    public function __construct(public $issueName, public $issueType, public $status, public $statusId, public $created)
    {
    }
}