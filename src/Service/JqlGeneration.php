<?php

namespace App\Service;

use App\Jira\Board\Configuration;
use JiraRestApi\Issue\JqlQuery;

class JqlGeneration
{
    const OPTIONS_PROJECT_KEY = 'project';
    const OPTIONS_EXCLUDE_KEY = 'exclude';

    public static function getJQlQueriesFromOptions(array $options, JqlQuery $jql=null): JqlQuery
    {
        if (empty($jql)) {
            $jql = new JqlQuery();
        }

        foreach ($options as $key => $option) {
            if (empty($option)) continue;

            switch ($key) {
                case self::OPTIONS_PROJECT_KEY:
                    $jql->addExpression(
                        JqlQuery::FIELD_PROJECT,
                        JqlQuery::OPERATOR_EQUALS, $option,
                    );
                    break;
                case self::OPTIONS_EXCLUDE_KEY:
                    $jql->addInExpression(
                        JqlQuery::FIELD_TYPE,
                        $option
                    );
                    break;
                default:
                    break;
            }
        }
        return $jql;
    }

    public static function getJQLQueryFromBoardConfig(Configuration $configuration, JqlQuery $jql=null): JqlQuery
    {
        if (empty($jql)) {
            $jql = new JqlQuery();
        }
        if ($configuration->hasSubQuery()) {
            $jql->addAnyExpression(JqlQuery::KEYWORD_AND . ' (' . $configuration->subQuery->query . ')');
        }
        return $jql;
    }
}

