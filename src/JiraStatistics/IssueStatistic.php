<?php

namespace App\JiraStatistics;

class IssueStatistic
{
    /** @var string */
    public $created;

    /** @var string */
    public $issueType;

    /** @var string */
    public $issueName;

    /** @var string */
    public $status;

    /** @var int */
    public $statusId;

    public function __construct(
        $issueName,
        $issueType,
        $status,
        $statusId,
        $created
    ){
        $this->created = $created;
        $this->issueName = $issueName;
        $this->issueType = $issueType;
        $this->status = $status;
        $this->statusId = $statusId;
    }
}