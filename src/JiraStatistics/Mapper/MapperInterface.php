<?php

namespace App\JiraStatistics\Mapper;

use App\JiraStatistics\SprintStatistics;

interface MapperInterface
{
    public function mapStatistics(SprintStatistics $sprintStatistics): array;
}