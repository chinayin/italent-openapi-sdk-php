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

use ITalentOpenSDK\Model\ScrollResult;
use PHPUnit\Framework\TestCase;

class ScrollResultTest extends TestCase
{
    public function testFromArrayWithFullData(): void
    {
        $data = [
            'scrollId' => 'DXF1ZXJ5QW5kRmV0Y2gBAAAAAAVrsaUWdnVycEd3OEFRRm02aEpHRFZQZ2htdw==',
            'total' => 100,
            'data' => [['id' => 1, 'name' => 'test']],
            'isLastData' => false,
        ];

        $result = ScrollResult::fromArray($data);

        $this->assertEquals('DXF1ZXJ5QW5kRmV0Y2gBAAAAAAVrsaUWdnVycEd3OEFRRm02aEpHRFZQZ2htdw==', $result->scrollId());
        $this->assertEquals(100, $result->total());
        $this->assertEquals([['id' => 1, 'name' => 'test']], $result->items());
        $this->assertTrue($result->hasMore());
    }

    public function testFromArrayWithEmptyData(): void
    {
        $data = [
            'scrollId' => '',
            'total' => 0,
            'data' => [],
            'isLastData' => true,
        ];

        $result = ScrollResult::fromArray($data);

        $this->assertEquals('', $result->scrollId());
        $this->assertEquals(0, $result->total());
        $this->assertEquals([], $result->items());
        $this->assertFalse($result->hasMore());
    }

    public function testFromArrayWithMissingFields(): void
    {
        $data = [];

        $result = ScrollResult::fromArray($data);

        $this->assertEquals('', $result->scrollId());
        $this->assertEquals(0, $result->total());
        $this->assertEquals([], $result->items());
        $this->assertFalse($result->hasMore());
    }

    public function testFromArrayWithPartialData(): void
    {
        $data = [
            'scrollId' => 'partial_scroll_id',
            'total' => 50,
        ];

        $result = ScrollResult::fromArray($data);

        $this->assertEquals('partial_scroll_id', $result->scrollId());
        $this->assertEquals(50, $result->total());
        $this->assertEquals([], $result->items());
        $this->assertFalse($result->hasMore());
    }

    public function testHasMoreWithEmptyData(): void
    {
        $result = ScrollResult::fromArray(['data' => []]);
        $this->assertFalse($result->hasMore());

        $result = ScrollResult::fromArray([]);
        $this->assertFalse($result->hasMore());
    }

    public function testHasMoreWithNonEmptyData(): void
    {
        // 只有data没有scrollId，hasMore应该返回false
        $result = ScrollResult::fromArray(['data' => [['test' => 'value']]]);
        $this->assertFalse($result->hasMore());

        // 同时有scrollId和data，hasMore应该返回true
        $result = ScrollResult::fromArray([
            'scrollId' => 'test-scroll-id',
            'data' => ['item1', 'item2'],
        ]);
        $this->assertTrue($result->hasMore());

        // 有scrollId但data为空，hasMore应该返回false
        $result = ScrollResult::fromArray([
            'scrollId' => 'test-scroll-id',
            'data' => [],
        ]);
        $this->assertFalse($result->hasMore());
    }

    public function testGettersReturnCorrectTypes(): void
    {
        $data = [
            'scrollId' => 'test_scroll_id',
            'total' => 42,
            'data' => [['key' => 'value']],
        ];

        $result = ScrollResult::fromArray($data);

        $this->assertEquals($data['scrollId'], $result->scrollId());
        $this->assertEquals($data['total'], $result->total());
        $this->assertEquals($data['data'], $result->items());
        $this->assertTrue($result->hasMore()); // 有scrollId和data就表示hasMore
    }

    public function testToArray(): void
    {
        $data = [
            'scrollId' => 'test_id',
            'total' => 10,
            'data' => [['test' => 'data']],
        ];

        $result = ScrollResult::fromArray($data);
        $array = $result->toArray();

        $expected = [
            'total' => 10,
            'hasMore' => true,
            'scrollId' => 'test_id',
            'data' => [['test' => 'data']],
        ];

        $this->assertEquals($expected, $array);
    }

    public function testInheritedModelMethods(): void
    {
        $data = [
            'scrollId' => 'test_id',
            'total' => 5,
            'data' => [['item' => 'value']],
        ];
        $result = ScrollResult::fromArray($data);

        // 测试继承自Model的方法
        $this->assertInstanceOf(\JsonSerializable::class, $result);

        // 测试方法返回正确的数据
        $toArrayResult = $result->toArray();
        $this->assertEquals($result->jsonSerialize(), $toArrayResult);

        // 测试JSON序列化
        $jsonString = json_encode($result);
        $this->assertNotEmpty($jsonString);
        $decoded = json_decode($jsonString, true);
        $this->assertEquals($toArrayResult, $decoded);

        // 测试toString
        $stringResult = (string)$result;
        $this->assertNotEmpty($stringResult);
        $this->assertEquals($jsonString, $stringResult);
    }
}
