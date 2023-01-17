<?php

namespace App\Command;

use App\JiraStatistics\Output;
use App\JiraStatistics\Writer\WriterInterface;
use App\Service\BoardConfigurationService;
use App\Service\IssueSelectionService;
use App\Service\JqlGeneration;
use JiraRestApi\Board\BoardService;
use JiraRestApi\Issue\JqlQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BoardGenerateMetricsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'jira:board:generate:task-metrics';

    /** @var IssueSelectionService */
    protected $issueAggregationService;

    /** @var BoardService */
    protected $boardService;

    protected BoardConfigurationService $boardConfigService;

    /** @var Output */
    private $statisticOutput;

    public function __construct(IssueSelectionService $issuesAggregation, WriterInterface $influxDbWriter)
    {
        $this->issueAggregationService = $issuesAggregation;
        $this->boardService = new BoardService();
        $this->boardConfigService = new BoardConfigurationService();

        $this->statisticOutput = new Output($influxDbWriter);

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Fetches jira issues for a sprint on an agile board');
        $this->addArgument(
            'boardid',
            InputArgument::REQUIRED,
            'The id of the Jira Agile board');
        $this->addOption(
            JqlGeneration::OPTIONS_PROJECT_KEY,
            'p',
            InputOption::VALUE_REQUIRED,
            'The project fetched tasks should belong to',
            false)
        ->addOption(
            JqlGeneration::OPTIONS_EXCLUDE_KEY,
            'x',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Exclude the listed issue types',
            [])
        ->addOption(
            JqlGeneration::OPTIONS_QUERY_KEY,
            '',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Add to the query with AND condition',
            []);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $boardConfig = $this->boardConfigService->getBoardConfig($input->getArgument('boardid'));
        $style = new SymfonyStyle($input, $output);
        $style->title('Gathering metrics on Board #' . $boardConfig->id);
        $style->writeln('Parsing board configuration....');

        $jql = JqlGeneration::getJQlQueriesFromOptions($input->getOptions());
        $jql = JqlGeneration::getJQLQueryFromBoardConfig($boardConfig, $jql);
        $jql->addExpression(JqlQuery::FIELD_STATUS, JqlQuery::OPERATOR_NOT_EQUALS, 'Backlog');

        $style->writeln('Fetching Ticket Statistics....');
        $issueStatistics = $this->issueAggregationService->getBoardTicketStatistics(
            $boardConfig,
            ['jql' => urlencode($jql->getQuery())],
            true
        );

        $this->statisticOutput->output($issueStatistics);

        $style->success('Aggregated metrics and stored them in InfluxDB');
        return 0;
    }
}

