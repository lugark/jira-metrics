<?php

namespace App\JiraStatistics;

class IssueStatistic
{
    public $created;
    public $issueType;
    public $issueName;
    public $status;

    public function __construct(
        $issueName,
        $issueType,
        $status,
        $created
    ){
        $this->created = $created;
        $this->issueName = $issueName;
        $this->issueType = $issueType;
        $this->status = $status;
    }
}