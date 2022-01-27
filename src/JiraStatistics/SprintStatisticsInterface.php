<?php

namespace App\JiraStatistics;

interface SprintStatisticsInterface
{
    public function getSprintGoal(): string;
    public function getSprintId(): int;
    public function getSprintName(): string;
    public function getSprintStart(): \DateTime;
}
