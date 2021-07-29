<?php

namespace App\Command;

use App\JiraStatistics\Mapper\InfluxDB\StatisticsByBoardStatus;
use App\JiraStatistics\Mapper\InfluxDB\StatisticsByBoardStatusDaily;
use App\JiraStatistics\Output;
use App\JiraStatistics\Writer\InfluxDBWriter;
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

class SprintGenerateMetricsCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'jira:sprint:generate:task-metrics';

    /** @var IssueAggregation */
    protected $issueAggregationService;

    /** @var BoardService */
    protected $boardService;

    /** @var Output */
    private $statisticOutput;

    public function __construct(IssueAggregation $issuesAggregation, InfluxDBWriter $influxDbWriter)
    {
        $this->issueAggregationService = $issuesAggregation;
        $this->boardService = new BoardService();

        $influxDbWriter->addStatisticsMapper(new StatisticsByBoardStatus());
        $influxDbWriter->addStatisticsMapper(new StatisticsByBoardStatusDaily());
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
            'project',
            'p',
            InputOption::VALUE_REQUIRED,
            'The project fetched tasks should belong to',
            false)
        ->addOption(
            'exclude',
            'x',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Exclude the listed issue types',
            []);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);
        $style->title('Gathering metrics on Agile Board #' . $input->getArgument('boardid'));
        $style->write('Getting active sprint board....');
        $sprints = $this->boardService->getBoardSprints($input->getArgument('boardid'), ['state' => 'active']);
        if ($sprints->count() === 0) {
            $style->error('No active sprint board found!');
            return 1;
        }

        /** @var Sprint $activeSprint */
        $activeSprint = $sprints[0];
        $style->writeln(
            sprintf('Found "%s" (ID:%d)', $activeSprint->getName(), $activeSprint->id)
        );
        $jql = JqlGeneration::getJQlQueriesFromOptions($input->getOptions());

        $issueStatistics = $this->issueAggregationService->getSprintTicketStatistics(
            $activeSprint,
            ['jql' => urlencode($jql->getQuery())],
            true
        );

        $this->statisticOutput->output($issueStatistics);

        $style->success('Aggregated metrics and stored them in InfluxDB');
        return 0;
    }
}

