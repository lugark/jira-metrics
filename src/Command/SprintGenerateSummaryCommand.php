<?php

namespace App\Command;

use App\JiraStatistics\Mapper\InfluxDB\StatsBySprint;
use App\JiraStatistics\Mapper\InfluxDB\StatsByType;
use App\JiraStatistics\Writer\InfluxDBWriter;
use App\JiraStatistics\Output;
use App\Service\IssueAggregation;
use InfluxDB\Client;
use InfluxDB\Database;
use InfluxDB\Point;
use JiraRestApi\Board\BoardService;
use JiraRestApi\Sprint\Sprint;
use JiraRestApi\Sprint\SprintService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SprintGenerateSummaryCommand extends Command
{
    protected static $defaultName = 'jira:sprint:generate:summary';

    /** @var IssueAggregation */
    protected $issueAggregationService;

    /** @var Output */
    protected $statisticOutput;

    /** @var BoardService */
    protected $boardService;

    public function __construct(IssueAggregation $issuesAggregation, InfluxDBWriter $influxDbWriter)
    {
        $this->issueAggregationService = $issuesAggregation;
        $this->boardService = new BoardService();
        $this->influxClient = new Client('localhost');

        $this->statisticOutput = new Output($influxDbWriter);
        $this->statisticOutput->addStatisticsMapper(new StatsByType());
        $this->statisticOutput->addStatisticsMapper(new StatsBySprint());

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

        $queryOptions = [];
        $project = $input->getOption('project');
        if ($project !== false) {
            $queryOptions['jql'] = urlencode('project = ' . $project);
        }

        $issueStatistics = $this->issueAggregationService->getSprintTicketStatistics(
            $activeSprint,
            $queryOptions
        );

        $this->statisticOutput->output($issueStatistics);

        return 0;
    }
}