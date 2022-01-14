<?php

namespace App\Command;

use App\JiraStatistics\Mapper\InfluxDB\Sprint\StatisticsBySprint;
use App\JiraStatistics\Mapper\InfluxDB\Sprint\StatisticsBySprintIssueType;
use App\JiraStatistics\Mapper\MySQL\SprintStatisticsMySQL;
use App\JiraStatistics\Writer\InfluxDBWriter;
use App\JiraStatistics\Output;
use App\JiraStatistics\Writer\MysqlWriter;
use App\JiraStatistics\Writer\WriterInterface;
use App\Service\IssueAggregation;
use App\Service\JqlGeneration;
use JiraRestApi\Board\BoardService;
use JiraRestApi\Sprint\Sprint;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SprintGenerateSummaryCommand extends Command
{
    protected static $defaultName = 'jira:sprint:generate:sprint-stats';

    /** @var IssueAggregation */
    protected $issueAggregationService;

    /** @var Output */
    protected $statisticOutput;

    /** @var BoardService */
    protected $boardService;

    public function __construct(IssueAggregation $issuesAggregation, WriterInterface $influxDbWriter, MysqlWriter  $mysqlWriter=null)
    {
        $this->issueAggregationService = $issuesAggregation;
        $this->boardService = new BoardService();

        $this->statisticOutput = new Output();
        $this->statisticOutput->addWriter($influxDbWriter);
        if (!empty($mysqlWriter)) {
            $this->statisticOutput->addWriter($mysqlWriter);
        }

        parent::__construct();
    }

    public function configure()
    {
        $this->setDescription('Generates a summary for the active sprint on the Jira Agile Board');
        $this->addArgument(
            'boardid',
            InputArgument::REQUIRED,
            'The id of the Jira Agile board');
        $this->addOption(
            'project',
            'p',
            InputOption::VALUE_REQUIRED,
            'The project fetched tasks should belong to',
            false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $boardId = $input->getArgument('boardid');
        $style = new SymfonyStyle($input, $output);
        $style->title('Gathering sprint information on Agile Board #' . $boardId);
        $style->write('Getting active sprint board....');
        $sprints = $this->boardService->getBoardSprints($boardId, ['state' => 'active']);
        if ($sprints->count() === 0) {
            $style->error('No active sprint board found!');
            return 1;
        }

        /** @var Sprint $activeSprint */
        $activeSprint = $sprints[0];
        $jql = JqlGeneration::getJQlQueriesFromOptions($input->getOptions());

        $issueStatistics = $this->issueAggregationService->getSprintTicketStatistics(
            $activeSprint,
            ['jql' => urlencode($jql->getQuery())],
        );

        $this->statisticOutput->output($issueStatistics);

        $style->success('Done creating statistic summary for the sprint');
        return 0;
    }
}
