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

use ITalentOpenSDK\Model\KeyValueItem;
use ITalentOpenSDK\Tests\TestCase;

class KeyValueItemTest extends TestCase
{
    public function testConstructor(): void
    {
        $item = new KeyValueItem('test_key', 'test_value');

        $this->assertEquals('test_key', $item->getKey());
        $this->assertEquals('test_value', $item->getValue());
    }

    public function testConstructorWithDifferentValueTypes(): void
    {
        // 测试字符串值
        $stringItem = new KeyValueItem('string_key', 'string_value');
        $this->assertEquals('string_value', $stringItem->getValue());

        // 测试数字值
        $numberItem = new KeyValueItem('number_key', 123);
        $this->assertEquals(123, $numberItem->getValue());

        // 测试数组值
        $arrayItem = new KeyValueItem('array_key', ['a', 'b', 'c']);
        $this->assertEquals(['a', 'b', 'c'], $arrayItem->getValue());

        // 测试布尔值
        $boolItem = new KeyValueItem('bool_key', true);
        $this->assertTrue($boolItem->getValue());

        // 测试null值
        $nullItem = new KeyValueItem('null_key', null);
        $this->assertNull($nullItem->getValue());
    }

    public function testSettersAndGetters(): void
    {
        $item = new KeyValueItem('initial_key', 'initial_value');

        // 测试设置新的key
        $item->setKey('new_key');
        $this->assertEquals('new_key', $item->getKey());

        // 测试设置新的value
        $item->setValue('new_value');
        $this->assertEquals('new_value', $item->getValue());

        // 测试设置复杂类型的value
        $complexValue = ['nested' => ['array' => 'value']];
        $item->setValue($complexValue);
        $this->assertEquals($complexValue, $item->getValue());
    }

    public function testToArray(): void
    {
        $item = new KeyValueItem('test_key', 'test_value');
        $array = $item->toArray();

        $expected = [
            'key' => 'test_key',
            'value' => 'test_value',
        ];

        $this->assertEquals($expected, $array);
    }

    public function testToArrayWithComplexValue(): void
    {
        $complexValue = [
            'nested' => 'data',
            'numbers' => [1, 2, 3],
            'boolean' => true,
        ];

        $item = new KeyValueItem('complex_key', $complexValue);
        $array = $item->toArray();

        $expected = [
            'key' => 'complex_key',
            'value' => $complexValue,
        ];

        $this->assertEquals($expected, $array);
    }

    public function testJsonSerializable(): void
    {
        $item = new KeyValueItem('json_key', 'json_value');

        // 测试 json_encode 会自动调用 jsonSerialize()
        $jsonString = json_encode($item, JSON_UNESCAPED_UNICODE);
        $expectedJson = '{"key":"json_key","value":"json_value"}';
        $this->assertEquals($expectedJson, $jsonString);

        // 测试 jsonSerialize 方法直接调用
        $this->assertEquals($item->toArray(), $item->jsonSerialize());
    }

    public function testToString(): void
    {
        $item = new KeyValueItem('string_key', 'string_value');

        // __toString() 应该返回 JSON 字符串
        $expectedString = '{"key":"string_key","value":"string_value"}';
        $this->assertEquals($expectedString, (string)$item);
    }

    public function testInheritedModelMethods(): void
    {
        $item = new KeyValueItem('test', 'value');

        // 测试继承自Model的方法
        $this->assertInstanceOf(\JsonSerializable::class, $item);

        // 测试方法返回正确的数据
        $toArrayResult = $item->toArray();
        $this->assertEquals($item->jsonSerialize(), $toArrayResult);

        // 测试toString
        $stringResult = (string)$item;
        $this->assertNotEmpty($stringResult);
        $this->assertEquals(json_encode($toArrayResult, JSON_UNESCAPED_UNICODE), $stringResult);
    }

    public function testPublicProperties(): void
    {
        $item = new KeyValueItem('initial_key', 'initial_value');

        // 测试直接访问公共属性
        $this->assertEquals('initial_key', $item->key);
        $this->assertEquals('initial_value', $item->value);

        // 测试直接设置公共属性
        $item->key = 'direct_key';
        $item->value = 'direct_value';

        $this->assertEquals('direct_key', $item->key);
        $this->assertEquals('direct_value', $item->value);
        $this->assertEquals('direct_key', $item->getKey());
        $this->assertEquals('direct_value', $item->getValue());
    }
}
