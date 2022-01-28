<?php

namespace App\Tests\JiraStatistics;

use App\JiraStatistics\AbstractStatistics;
use App\JiraStatistics\Aggregator\AggregationItem;
use App\JiraStatistics\Aggregator\AggregationResult;
use App\JiraStatistics\Aggregator\AggregatorInterface;
use App\JiraStatistics\IssueInformation;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class returnAggregator implements AggregatorInterface
{
    public function getName(): string
    {
        return 'IssueData';
    }

    public function execute(string $aggregation, $payload): AggregationResult
    {
        $result = new AggregationResult();
        $result->addItem(new AggregationItem('data', $payload));
        return $result;
    }
}

class AbastractIssueStatisticsTest extends TestCase
{

    /**
     * @var AbstractStatistics
     */
    protected $sut;

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }

    public function setUp(): void
    {
        $this->sut = $this->getMockForAbstractClass(AbstractStatistics::class);
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testNewInstance()
    {
        $this->assertEmpty($this->sut->getIssueCountsByType());
    }

    /**
     * @covers AbstractStatistics::flipBoardColumnMapping
     */
    public function testFlipBoardColumns()
    {
        $result = $this->invokeMethod
        (
            $this->sut,
            'flipBoardColumnMapping', 
            [ ['ToDo'=>[99,11], 'Done'=>[33]] ]
        );
        Assert::assertEquals('ToDo', $result[99]);
        Assert::assertEquals('ToDo', $result[11]);
        Assert::assertEquals('Done', $result[33]);
    }

    public function testIssueInformationAggregator()
    {
        $this->sut->addIssueInformation(new IssueInformation('Issue1', 'task', 'Done', 33, '', 0.0));
        $this->sut->addIssueInformation(new IssueInformation('Issue2', 'task', 'Done', 33, '', 0.0));
        $this->sut->addIssueInformation(new IssueInformation('Issue3', 'Bug', 'ToDo', 99, '', 0.0));
        $this->sut->addAggregator(new returnAggregator());

        $result = $this->sut->aggregate('IssueData', 'egal')->getValueByKey('data');        
        Assert::assertEquals(3, count($result));
        Assert::assertEquals(new IssueInformation('Issue2', 'task', 'Done', 33, '', 0.0), $result[1]);
    }
}
