<?php
/**
 * DefinitionTest
 *
 * @package     Rootwork\Report
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report;

use Rootwork\Test\TestCase;

class DefinitionTest extends TestCase
{

    public function testJson()
    {
        $expected = json_encode([
            'title' => 'Foo Report',
            'columns' => [
                ['name' => 'id', 'display' => 'ID', 'type' => 'integer', 'format' => 'number', 'total' => false],
                ['name' => 'name', 'display' => 'Name', 'type' => 'string', 'format' => null, 'total' => false],
                ['name' => 'score', 'display' => 'Score', 'type' => 'integer', 'format' => 'number', 'total' => true],
            ],
            'variables' => [
                [
                    'name' => 'multiplier',
                    'display' => 'Multiplier',
                    'type' => 'number',
                    'default' => 1,
                    'options' => [],
                    'format' => null,
                    'description' => 'Enter a multiplier',
                ],
            ],
        ]);

        $sut = new Definition();
        $sut->setTitle('Foo Report')
            ->addColumn(new Column('id', 'ID', Column::TYPE_INTEGER, Column::FORMAT_NUMBER))
            ->addColumn(new Column('name', 'Name', Column::TYPE_STRING))
            ->addColumn(new Column('score', 'Score', Column::TYPE_INTEGER, Column::FORMAT_NUMBER, true))
            ->addVariable(new Variable(
                'multiplier', 'Multiplier', Variable::TYPE_NUMBER, 1, [], null, 'Enter a multiplier'
            ));
        $actual = json_encode($sut);

        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }
}