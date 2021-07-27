<?php

namespace App\Service;

use App\Jira\Board\Configuration;

class JqlGeneration
{
    const OPTIONS_PROJECT_KEY = 'project';
    const OPTIONS_EXCLUDE_KEY = 'exclude';

    public static function getProjectCondition(string $project): string
    {
        return empty($project) ? '' : 'project = ' . $project;
    }

    public static function getExcludeTypesCondition(array $types): string
    {
        return empty($types) ? '' : 'type not in (' . implode(',', $types) . ')';
    }

    public static function combineQueries(array $queries, bool $urlEncode = true): string
    {
        if (empty($queries)) {
            return '';
        }
        $queries = array_map(function ($str) { return sprintf("(%s)", $str); }, array_filter($queries));
        $combined = implode(' AND ', $queries);
        return $urlEncode ? urlencode($combined) : $combined;
    }

    public static function getJQlQueriesFromOptions(array $options): array
    {
        $queries = [];
        foreach ($options as $key => $option) {
            switch ($key) {
                case self::OPTIONS_PROJECT_KEY:
                    $queries[] = !$option ? '' : JqlGeneration::getProjectCondition($option[0]);
                    break;
                case self::OPTIONS_EXCLUDE_KEY:
                    $queries[] = JqlGeneration::getExcludeTypesCondition($option);
                    break;
                default:
                    break;
            }
        }

        return $queries;
    }

    public static function getJQLQueriesFromBoardConfig(Configuration $configuration): array
    {
        return $configuration->hasSubQuery() ? [$configuration->subQuery->query] : [];
    }
}

