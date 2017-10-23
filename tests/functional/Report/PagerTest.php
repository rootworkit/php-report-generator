<?php
/**
 * PagerTest
 *
 * @package     Rootwork\Report
 * @copyright   Copyright (c) 2017 Rootwork InfoTech LLC
 * @license     MIT
 * @author      Mike Soule <mike@rootwork.it>
 * @filesource
 */

namespace Rootwork\Report;

use Rootwork\Test\TestCase;

class PagerTest extends TestCase
{

    /**
     * Test serializing pager to JSON.
     */
    public function testJson()
    {
        $expected = json_encode([
            'page'      => 3,
            'pageCount' => 10,
            'rowCount'  => 300,
            'limit'     => 10,
            'active'    => false,
        ]);

        $sut = new Pager(3, 10, 300, 10, false);
        $actual = json_encode($sut);

        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }

    /**
     * Test getting paged rows.
     *
     * @param array  $expectedRows
     * @param string $expectedJson
     * @param int    $page
     * @param int    $limit
     * @param array  $rows
     *
     * @dataProvider provideGetPagedRows
     */
    public function testGetPagedRows(array $expectedRows, $expectedJson, $page, $limit, array $rows)
    {
        $sut = new Pager();
        $sut->setPage($page)->setLimit($limit)->setActive(true);
        $actualRows = $sut->getPagedRows($rows);
        $actualJson = json_encode($sut);

        $this->assertEquals($expectedRows, $actualRows);
        $this->assertJsonStringEqualsJsonString($expectedJson, $actualJson);
    }

    /**
     * Provides data for testing getPagedRows().
     *
     * @return array
     */
    public function provideGetPagedRows()
    {
        $rows = [];

        foreach (range(1, 100) as $num) {
            $rows[] = [
                'id' => $num,
                'name' => "Name $num",
                'double' => $num * 2,
            ];
        }

        return [
            [
                [
                    ['id' => 21, 'name' => 'Name 21', 'double' => 42],
                    ['id' => 22, 'name' => 'Name 22', 'double' => 44],
                    ['id' => 23, 'name' => 'Name 23', 'double' => 46],
                    ['id' => 24, 'name' => 'Name 24', 'double' => 48],
                    ['id' => 25, 'name' => 'Name 25', 'double' => 50],
                    ['id' => 26, 'name' => 'Name 26', 'double' => 52],
                    ['id' => 27, 'name' => 'Name 27', 'double' => 54],
                    ['id' => 28, 'name' => 'Name 28', 'double' => 56],
                    ['id' => 29, 'name' => 'Name 29', 'double' => 58],
                    ['id' => 30, 'name' => 'Name 30', 'double' => 60],
                ],
                json_encode([
                    'page'      => 3,
                    'pageCount' => 10,
                    'rowCount'  => 100,
                    'limit'     => 10,
                    'active'    => true,
                ]),
                3,
                10,
                $rows,
            ],
            [
                [
                    ['id' => 1, 'name' => 'Name 1', 'double' => 2],
                    ['id' => 2, 'name' => 'Name 2', 'double' => 4],
                    ['id' => 3, 'name' => 'Name 3', 'double' => 6],
                    ['id' => 4, 'name' => 'Name 4', 'double' => 8],
                    ['id' => 5, 'name' => 'Name 5', 'double' => 10],
                    ['id' => 6, 'name' => 'Name 6', 'double' => 12],
                    ['id' => 7, 'name' => 'Name 7', 'double' => 14],
                    ['id' => 8, 'name' => 'Name 8', 'double' => 16],
                    ['id' => 9, 'name' => 'Name 9', 'double' => 18],
                    ['id' => 10, 'name' => 'Name 10', 'double' => 20],
                ],
                json_encode([
                    'page'      => 1,
                    'pageCount' => 10,
                    'rowCount'  => 100,
                    'limit'     => 10,
                    'active'    => true,
                ]),
                1,
                10,
                $rows,
            ],
            [
                [
                    ['id' => 91, 'name' => 'Name 91', 'double' => 182],
                    ['id' => 92, 'name' => 'Name 92', 'double' => 184],
                    ['id' => 93, 'name' => 'Name 93', 'double' => 186],
                    ['id' => 94, 'name' => 'Name 94', 'double' => 188],
                    ['id' => 95, 'name' => 'Name 95', 'double' => 190],
                    ['id' => 96, 'name' => 'Name 96', 'double' => 192],
                    ['id' => 97, 'name' => 'Name 97', 'double' => 194],
                    ['id' => 98, 'name' => 'Name 98', 'double' => 196],
                    ['id' => 99, 'name' => 'Name 99', 'double' => 198],
                    ['id' => 100, 'name' => 'Name 100', 'double' => 200],
                ],
                json_encode([
                    'page'      => 10,
                    'pageCount' => 10,
                    'rowCount'  => 100,
                    'limit'     => 10,
                    'active'    => true,
                ]),
                10,
                10,
                $rows,
            ],
            [
                [],
                json_encode([
                    'page'      => 11,
                    'pageCount' => 10,
                    'rowCount'  => 100,
                    'limit'     => 10,
                    'active'    => true,
                ]),
                11,
                10,
                $rows,
            ],
        ];
    }
}