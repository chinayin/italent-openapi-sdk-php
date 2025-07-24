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
        $result = ScrollResult::fromArray(['data' => [['test' => 'value']]]);
        $this->assertTrue($result->hasMore());

        $result = ScrollResult::fromArray(['data' => ['item1', 'item2']]);
        $this->assertTrue($result->hasMore());
    }

    public function testGettersReturnCorrectTypes(): void
    {
        $data = [
            'scrollId' => 'test_scroll_id',
            'total' => 42,
            'data' => [['key' => 'value']],
            'isLastData' => true,
        ];

        $result = ScrollResult::fromArray($data);

        $this->assertIsString($result->scrollId());
        $this->assertIsInt($result->total());
        $this->assertIsArray($result->items());
        $this->assertIsBool($result->hasMore());
    }

    public function testToArrayInheritedFromModel(): void
    {
        $data = [
            'scrollId' => 'test_id',
            'total' => 10,
            'data' => [['test' => 'data']],
        ];

        $result = ScrollResult::fromArray($data);
        $array = $result->toArray();
        $this->assertIsArray($array);
        $this->assertArrayHasKey('scrollId', $array);
        $this->assertArrayHasKey('total', $array);
        $this->assertArrayHasKey('data', $array);
    }
}
