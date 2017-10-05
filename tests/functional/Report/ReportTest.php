<?php
/**
 * ReportTest
 *
 * @package     Rootwork\Report
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report;

use Rootwork\Test\TestCase;

class ReportTest extends TestCase
{

    /**
     * @var FooReport
     */
    protected $sut;

    /**
     * @var Definition
     */
    protected $definition;

    /**
     * Set up the test.
     */
    public function setUp()
    {
        $columns    = [
            new Column('id', 'ID', Column::TYPE_INTEGER, Column::FORMAT_NUMBER),
            new Column('name', 'Name', Column::TYPE_STRING),
            new Column('score', 'Score', Column::TYPE_INTEGER, Column::FORMAT_NUMBER, true),
        ];
        $variable   = new Variable('multiplier', 'Multiplier', Variable::TYPE_NUMBER, 1);
        $definition = new Definition();

        $definition->setTitle('Foo Report');
        $definition->setClass(FooReport::class);
        $definition->setColumns($columns);
        $definition->setVariables([$variable]);

        $this->definition = $definition;
        $this->sut = $definition->getReport();
    }

    /**
     * Test running a report.
     *
     * @param array $expectedData
     * @param array $expectedTotals
     * @param array $values
     *
     * @dataProvider provideRun
     */
    public function testRun(array $expectedData, array $expectedTotals, array $values)
    {
        $this->definition->setVariableValues($values);
        $sut = $this->sut;
        $actualData = $sut->run();
        $actualTotals = $sut->getTotals();

        $this->assertEquals($expectedData, $actualData);
        $this->assertEquals($expectedTotals, $actualTotals);
    }

    /**
     * Provides data for testing Report::run().
     *
     * @return array
     */
    public function provideRun()
    {
        return [
            [
                [
                    ['id' => 1, 'name' => 'Foo', 'score' => 1],
                    ['id' => 2, 'name' => 'Bar', 'score' => 2],
                    ['id' => 3, 'name' => 'Baz', 'score' => 3],
                    ['id' => 4, 'name' => 'Qux', 'score' => 5],
                    ['id' => 5, 'name' => 'Gar', 'score' => 8],
                ],
                [null, null, 19],
                ['multiplier' => 1],
            ],
            [
                [
                    ['id' => 1, 'name' => 'Foo', 'score' => 2],
                    ['id' => 2, 'name' => 'Bar', 'score' => 4],
                    ['id' => 3, 'name' => 'Baz', 'score' => 6],
                    ['id' => 4, 'name' => 'Qux', 'score' => 10],
                    ['id' => 5, 'name' => 'Gar', 'score' => 16],
                ],
                [null, null, 38],
                ['multiplier' => 2],
            ],
            [
                [
                    ['id' => 1, 'name' => 'Foo', 'score' => 10],
                    ['id' => 2, 'name' => 'Bar', 'score' => 20],
                    ['id' => 3, 'name' => 'Baz', 'score' => 30],
                    ['id' => 4, 'name' => 'Qux', 'score' => 50],
                    ['id' => 5, 'name' => 'Gar', 'score' => 80],
                ],
                [null, null, 190],
                ['multiplier' => 10],
            ],
        ];
    }

    /**
     * Test serializing a report to JSON.
     */
    public function testJson()
    {
        $expected = json_encode([
            'title' => 'Foo Report',
            'columns' => [
                ['name' => 'ID', 'type' => 'integer'],
                ['name' => 'Name', 'type' => 'string'],
                ['name' => 'Score', 'type' => 'integer'],
            ],
            'rows' => [
                ['id' => 1, 'name' => 'Foo', 'score' => 1],
                ['id' => 2, 'name' => 'Bar', 'score' => 2],
                ['id' => 3, 'name' => 'Baz', 'score' => 3],
                ['id' => 4, 'name' => 'Qux', 'score' => 5],
                ['id' => 5, 'name' => 'Gar', 'score' => 8],
            ],
            'totals' => [null, null, 19],
        ]);

        $this->definition->setVariableValues(['multiplier' => 1]);
        $sut = $this->sut;
        $sut->run();
        $actual = json_encode($sut);

        $this->assertEquals($expected, $actual);
    }
}

class FooReport extends ReportAbstract implements ReportInterface
{

    /**
     * Run the report and return results.
     *
     * @return array[]
     */
    public function run()
    {
        $multiplier = $this->definition->getVariable('multiplier')->getValue();
        $this->rows = [
            ['id' => 1, 'name' => 'Foo', 'score' => 1 * $multiplier],
            ['id' => 2, 'name' => 'Bar', 'score' => 2 * $multiplier],
            ['id' => 3, 'name' => 'Baz', 'score' => 3 * $multiplier],
            ['id' => 4, 'name' => 'Qux', 'score' => 5 * $multiplier],
            ['id' => 5, 'name' => 'Gar', 'score' => 8 * $multiplier],
        ];

        return $this->rows;
    }
}