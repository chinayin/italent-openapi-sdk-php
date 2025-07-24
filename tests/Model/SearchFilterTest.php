<?php

declare(strict_types=1);

/*
 * This file is part of the ITalent OpenAPI SDK for PHP.
 *
 * (c) chinayin <whereismoney@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ITalentOpenSDK\Tests\Model;

use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Tests\TestCase;

class SearchFilterTest extends TestCase
{
    public function testBasicProperties(): void
    {
        $filter = new SearchFilter();

        $filter->setStartTime('2021-01-01T00:00:00');
        $this->assertEquals('2021-01-01T00:00:00', $filter->getStartTime());

        $filter->setStopTime('2021-01-02T00:00:00');
        $this->assertEquals('2021-01-02T00:00:00', $filter->getStopTime());

        $filter->setTimeWindowQueryType('1');
        $this->assertEquals('1', $filter->getTimeWindowQueryType());

        $filter->setScrollId('test-scroll-id');
        $this->assertEquals('test-scroll-id', $filter->getScrollId());

        $filter->setCapacity(100);
        $this->assertEquals(100, $filter->getCapacity());

        $filter->setIsWithDeleted(true);
        $this->assertTrue($filter->getIsWithDeleted());
    }

    public function testSortMethods(): void
    {
        $filter = new SearchFilter();

        // 测试 addSort 方法
        $filter->addSort('Name', '1');
        $filter->addSort('Age', '2');

        $expected = ['Name' => '1', 'Age' => '2'];
        $this->assertEquals($expected, $filter->getSort());

        // 测试 setSort 方法
        $newSort = ['Code' => '1', 'Status' => '2'];
        $filter->setSort($newSort);
        $this->assertEquals($newSort, $filter->getSort());
    }

    public function testExtQueries(): void
    {
        $filter = new SearchFilter();

        // 测试 addExtQuery 方法
        $filter->addExtQuery('field1', 5, ['value1']);
        $filter->addExtQuery('field2', 3, ['value2', 'value3']);

        $expected = [
            [
                'fieldName' => 'field1',
                'queryType' => 5,
                'values' => ['value1'],
            ],
            [
                'fieldName' => 'field2',
                'queryType' => 3,
                'values' => ['value2', 'value3'],
            ],
        ];

        $this->assertEquals($expected, $filter->getExtQueries());
    }

    public function testColumns(): void
    {
        $filter = new SearchFilter();

        // 测试 addColumn 方法
        $filter->addColumn('Name');
        $filter->addColumn('Code');

        $this->assertEquals(['Name', 'Code'], $filter->getColumns());

        // 测试 setColumns 方法
        $columns = ['Name', 'OId', 'Code', 'Status'];
        $filter->setColumns($columns);
        $this->assertEquals($columns, $filter->getColumns());
    }

    public function testExtraParams(): void
    {
        $filter = new SearchFilter();

        $filter->setExtraParam('customField1', 'value1');
        $filter->setExtraParam('customField2', ['array', 'value']);

        $this->assertEquals('value1', $filter->getExtraParam('customField1'));
        $this->assertEquals(['array', 'value'], $filter->getExtraParam('customField2'));
        $this->assertNull($filter->getExtraParam('nonexistent'));

        $expected = [
            'customField1' => 'value1',
            'customField2' => ['array', 'value'],
        ];
        $this->assertEquals($expected, $filter->getExtraParams());
    }

    public function testToArray(): void
    {
        $filter = new SearchFilter();

        $filter
            ->setStartTime('2021-01-01T00:00:00')
            ->setStopTime('2021-01-02T00:00:00')
            ->setTimeWindowQueryType('1')
            ->setCapacity(100)
            ->setIsWithDeleted(false)
            ->addSort('Name', '1')
            ->addExtQuery('field1', 5, ['value1'])
            ->setColumns(['Name', 'Code'])
            ->setExtraParam('customField', 'customValue');

        $result = $filter->toArray();

        $expected = [
            'startTime' => '2021-01-01T00:00:00',
            'stopTime' => '2021-01-02T00:00:00',
            'timeWindowQueryType' => '1',
            'capacity' => 100,
            'sort' => ['Name' => '1'],
            'extQueries' => [
                [
                    'fieldName' => 'field1',
                    'queryType' => 5,
                    'values' => ['value1'],
                ],
            ],
            'isWithDeleted' => false,
            'columns' => ['Name', 'Code'],
            'customField' => 'customValue',
        ];

        $this->assertEquals($expected, $result);
    }

    public function testToArrayWithNullValues(): void
    {
        $filter = new SearchFilter();

        // 只设置部分值
        $filter
            ->setStartTime('2021-01-01T00:00:00')
            ->setCapacity(50)
            ->setExtraParam('custom', 'value');

        $result = $filter->toArray();

        $expected = [
            'startTime' => '2021-01-01T00:00:00',
            'capacity' => 50,
            'custom' => 'value',
        ];

        $this->assertEquals($expected, $result);

        // 确保 null 值不会出现在结果中
        $this->assertArrayNotHasKey('stopTime', $result);
        $this->assertArrayNotHasKey('scrollId', $result);
        $this->assertArrayNotHasKey('sort', $result);
    }

    public function testFluentInterface(): void
    {
        $filter = new SearchFilter();

        // 测试链式调用
        $result = $filter
            ->setStartTime('2021-01-01T00:00:00')
            ->setCapacity(100)
            ->addSort('Name', '1')
            ->setExtraParam('test', 'value');

        $this->assertInstanceOf(SearchFilter::class, $result);
        $this->assertSame($filter, $result);
    }
}
