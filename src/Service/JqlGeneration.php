<?php

namespace App\Service;

use App\Jira\Board\Configuration;
use JiraRestApi\Issue\JqlQuery;

class JqlGeneration
{
    final const OPTIONS_PROJECT_KEY = 'project';
    final const OPTIONS_EXCLUDE_KEY = 'exclude';
    final const OPTIONS_QUERY_KEY = 'query';

    public static function getJQlQueriesFromOptions(array $options, JqlQuery $jql=null): JqlQuery
    {
        if (empty($jql)) {
            $jql = new JqlQuery();
        }

        $queriesAdded = 0;
        foreach ($options as $key => $option) {
            if (empty($option)) continue;

            switch ($key) {
                case self::OPTIONS_PROJECT_KEY:
                    $jql->addExpression(
                        JqlQuery::FIELD_PROJECT,
                        JqlQuery::OPERATOR_EQUALS, $option,
                    );
                    $queriesAdded++;
                    break;
                case self::OPTIONS_EXCLUDE_KEY:
                    $jql->addNotInExpression(
                        JqlQuery::FIELD_TYPE,
                        $option
                    );
                    $queriesAdded++;
                    break;
                default:
                    break;
            }
        }

        if (!empty($options[self::OPTIONS_QUERY_KEY])) {
            $prefix = $queriesAdded > 0 ? JqlQuery::KEYWORD_AND . ' ' : '';
            $jql->addAnyExpression($prefix . $options[self::OPTIONS_QUERY_KEY][0]);
        }
        return $jql;
    }

    public static function getJQLQueryFromBoardConfig(Configuration $configuration, JqlQuery $jql=null): JqlQuery
    {
        $prefix = JqlQuery::KEYWORD_AND . ' ';
        if (empty($jql)) {
            $jql = new JqlQuery();
            $prefix = '';
        }

        if ($configuration->hasSubQuery()) {
            $jql->addAnyExpression($prefix . '(' . $configuration->subQuery->query . ')');
        }

        return $jql;
    }
}

