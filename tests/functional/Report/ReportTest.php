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
     * Test running a report.
     *
     * @param array $expectedData
     * @param array $expectedTotals
     * @param array $parameters
     *
     * @dataProvider provideRun
     */
    public function testRun(array $expectedData, array $expectedTotals, array $parameters)
    {
        $sut = new FooReport();
        $sut->setParameters($parameters);
        $sut->run();
        $actualData   = $sut->getRows();
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
            'paging' => null,
            'columns' => [
                ['name' => 'ID', 'type' => 'integer', 'format' => 'number'],
                ['name' => 'Name', 'type' => 'string', 'format' => null],
                ['name' => 'Score', 'type' => 'integer', 'format' => 'number'],
            ],
            'rows' => [
                ['id' => 1, 'name' => 'Foo', 'score' => 1],
                ['id' => 2, 'name' => 'Bar', 'score' => 2],
                ['id' => 3, 'name' => 'Baz', 'score' => 3],
                ['id' => 4, 'name' => 'Qux', 'score' => 5],
                ['id' => 5, 'name' => 'Gar', 'score' => 8],
            ],
            'order' => [],
            'totals' => [null, null, 19],
        ]);

        $sut = new FooReport();
        $sut->setParameters(['multiplier' => 1]);
        $sut->run();
        $actual = json_encode($sut);

        $this->assertEquals($expected, $actual);
    }
}

class FooReport extends ReportAbstract implements ReportInterface
{

    /**
     * Define the report.
     */
    protected function define()
    {
        $this->getDefinition()
            ->setTitle('Foo Report')
            ->setColumns([
                new Column('id', 'ID', Column::TYPE_INTEGER, Column::FORMAT_NUMBER),
                new Column('name', 'Name', Column::TYPE_STRING),
                new Column('score', 'Score', Column::TYPE_INTEGER, Column::FORMAT_NUMBER, true),
            ])
            ->setVariables([new Variable('multiplier', 'Multiplier', Variable::TYPE_NUMBER, 1)]);
    }

    /**
     * Run the report and return results.
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
    }
}