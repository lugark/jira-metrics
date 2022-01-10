<?php

namespace App\Tests\Service;

use App\Jira\Board\Configuration;
use App\Jira\Board\Configuration\SubQuery;
use App\Service\JqlGeneration;
use PHPUnit\Framework\TestCase;

class JqlGenerationTest extends TestCase
{
    private $boardConfig = null;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    /** @dataProvider getBoardConfigs */
    public function testGetJQLQueryFromBoardConfig($boardConfig, $expectedQuery)
    {
        $jql = JqlGeneration::getJQLQueryFromBoardConfig($boardConfig);
        $this->assertEquals($expectedQuery, $jql->getQuery());
    }

    public function getBoardConfigs()
    {
        $boardConfig = new Configuration();
        $subQuery = new SubQuery();
        $subQuery->query = 'This is a Test!';
        $boardConfig->subQuery = $subQuery;

        return [
            'noSubQuery' => [
                (new Configuration()),
                ''
            ],
            'subQuery' => [
                $boardConfig,
                ' (This is a Test!)'
            ]
        ];
    }

    /** @dataProvider getCommandOptions */
    public function testGetJQlQueriesFromOptions($options, $expectedQuery)
    {
        $jql = JqlGeneration::getJQlQueriesFromOptions($options);
        $this->assertEquals($expectedQuery, $jql->getQuery());
    }

    public function getCommandOptions()
    {
        return[
            'projectAndOneExclude' => [
                [
                    "project" => "SHS2PAY",
                    "exclude" =>["Epic"],
                    "help" =>  (false),
                    "quiet" =>  (false)
                ],
                '"project" = "SHS2PAY" and "type" not in ("Epic")'
            ],
            'projectAndTwoExclude' => [
                [
                    "project" => "SHS2PAY",
                    "exclude" =>["Epic", "Task"],
                    "help" =>  (false),
                    "quiet" =>  (false)
                ],
                '"project" = "SHS2PAY" and "type" not in ("Epic", "Task")'
            ],
        'noProjectAndOneExclude' => [
                [
                    "project" => "",
                    "exclude" =>["Epic"],
                    "help" =>  (false),
                    "quiet" =>  (false)
                ],
                '"type" not in ("Epic")'
            ],
            'nothingSet' => [
                [
                    "project" => "",
                    "exclude" =>[],
                    "help" =>  (false),
                    "quiet" =>  (false)
                ],
                ''
            ],
        ];
    }

}
