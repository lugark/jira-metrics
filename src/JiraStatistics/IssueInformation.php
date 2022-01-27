<?php

namespace App\JiraStatistics;

class IssueInformation
{
    protected ?string $created;

    protected string $issueType;

    public string $issueName;

    protected string $status;

    protected int $statusId;

    protected ?float $estimation;

    public function __construct(
        $issueName,
        $issueType,
        $status,
        $statusId,
        $created,
        $estimation
    ){
        $this->created = $created;
        $this->issueName = $issueName;
        $this->issueType = $issueType;
        $this->status = $status;
        $this->statusId = $statusId;
        $this->estimation = $estimation;
    }

    public function getCreated(): ?string
    {
        return $this->created;
    }

    public function getIssueType(): string
    {
        return $this->issueType;
    }

    public function getIssueName(): string
    {
        return $this->issueName;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStatusId(): int
    {
        return $this->statusId;
    }

    public function getEstimation(): ?float
    {
        return $this->estimation;
    }
}
