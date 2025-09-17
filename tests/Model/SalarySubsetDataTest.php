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

use ITalentOpenSDK\Model\SalarySubsetData;
use ITalentOpenSDK\Tests\TestCase;

class SalarySubsetDataTest extends TestCase
{
    public function testBasicProperties(): void
    {
        $data = new SalarySubsetData();

        // 测试员工ID
        $data->setStaffId('EMP001');
        $this->assertEquals('EMP001', $data->getStaffId());

        // 测试开始日期
        $data->setStartDate('2023-01-01 00:00:00');
        $this->assertEquals('2023-01-01 00:00:00', $data->getStartDate());

        // 测试结束日期
        $data->setStopDate('2023-12-31 23:59:59');
        $this->assertEquals('2023-12-31 23:59:59', $data->getStopDate());

        // 测试是否失效
        $data->setIsInvalid(false);
        $this->assertFalse($data->getIsInvalid());

        // 测试是否生效
        $data->setStatus(true);
        $this->assertTrue($data->getStatus());

        // 测试项目名称
        $data->setItemName('项目1');
        $this->assertEquals('项目1', $data->getItemName());

        // 测试数值
        $data->setNumericVal(1000.50);
        $this->assertEquals(1000.50, $data->getNumericVal());

        // 测试备注
        $data->setNote('测试备注');
        $this->assertEquals('测试备注', $data->getNote());
    }

    public function testCustomFields(): void
    {
        $data = new SalarySubsetData();

        // 测试设置自定义字段字典
        $customFields = [
            'extone_600399_783312538' => '文本说明',
            'extyzzjyg_600399_1334600576' => '660006278',
        ];
        $data->setCustomFields($customFields);
        $this->assertEquals($customFields, $data->getCustomFields());

        // 测试添加单个自定义字段
        $data->addCustomField('new_field', 'new_value');
        $expected = array_merge($customFields, ['new_field' => 'new_value']);
        $this->assertEquals($expected, $data->getCustomFields());
    }

    public function testAddCustomFieldToEmptyFields(): void
    {
        $data = new SalarySubsetData();

        // 在空的自定义字段上添加
        $data->addCustomField('first_field', 'first_value');
        $this->assertEquals(['first_field' => 'first_value'], $data->getCustomFields());

        // 继续添加
        $data->addCustomField('second_field', 'second_value');
        $expected = [
            'first_field' => 'first_value',
            'second_field' => 'second_value',
        ];
        $this->assertEquals($expected, $data->getCustomFields());
    }

    public function testFluentInterface(): void
    {
        $data = new SalarySubsetData();

        // 测试链式调用
        $result = $data
            ->setStaffId('EMP002')
            ->setStartDate('2023-06-01 00:00:00')
            ->setStatus(true)
            ->setNumericVal(2500.75)
            ->setNote('链式调用测试');

        $this->assertInstanceOf(SalarySubsetData::class, $result);
        $this->assertSame($data, $result);

        // 验证设置的值
        $this->assertEquals('EMP002', $data->getStaffId());
        $this->assertEquals('2023-06-01 00:00:00', $data->getStartDate());
        $this->assertTrue($data->getStatus());
        $this->assertEquals(2500.75, $data->getNumericVal());
        $this->assertEquals('链式调用测试', $data->getNote());
    }

    public function testToArrayWithAllFields(): void
    {
        $data = new SalarySubsetData();
        $data
            ->setStaffId('EMP003')
            ->setStartDate('2023-01-01 00:00:00')
            ->setStopDate('2023-12-31 23:59:59')
            ->setIsInvalid(false)
            ->setStatus(true)
            ->setItemName('项目2')
            ->setNumericVal(3000.00)
            ->setNote('完整数据测试')
            ->setCustomFields([
                'field1' => 'value1',
                'field2' => 'value2',
            ]);

        $result = $data->toArray();

        $expected = [
            'staffId' => 'EMP003',
            'startDate' => '2023-01-01 00:00:00',
            'stopDate' => '2023-12-31 23:59:59',
            'isInvalid' => false,
            'status' => true,
            'itemName' => '项目2',
            'numericVal' => 3000.00,
            'note' => '完整数据测试',
            'customFields' => [
                'field1' => 'value1',
                'field2' => 'value2',
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    public function testToArrayWithPartialFields(): void
    {
        $data = new SalarySubsetData();
        $data
            ->setStaffId('EMP004')
            ->setStatus(true)
            ->setNumericVal(1500.00);

        $result = $data->toArray();

        $expected = [
            'staffId' => 'EMP004',
            'status' => true,
            'numericVal' => 1500.00,
        ];

        $this->assertEquals($expected, $result);

        // 确保null值不会出现在结果中
        $this->assertArrayNotHasKey('startDate', $result);
        $this->assertArrayNotHasKey('stopDate', $result);
        $this->assertArrayNotHasKey('isInvalid', $result);
        $this->assertArrayNotHasKey('itemName', $result);
        $this->assertArrayNotHasKey('note', $result);
        $this->assertArrayNotHasKey('customFields', $result);
    }

    public function testToArrayWithEmptyData(): void
    {
        $data = new SalarySubsetData();
        $result = $data->toArray();

        $this->assertEquals([], $result);
    }

    public function testJsonSerializable(): void
    {
        $data = new SalarySubsetData();
        $data
            ->setStaffId('EMP005')
            ->setNumericVal(2000.00)
            ->setNote('JSON测试');

        // 测试 json_encode 会自动调用 jsonSerialize()
        $jsonString = json_encode($data, JSON_UNESCAPED_UNICODE);
        $expectedJson = '{"staffId":"EMP005","numericVal":2000,"note":"JSON测试"}';
        $this->assertEquals($expectedJson, $jsonString);

        // 测试 jsonSerialize 方法直接调用
        $this->assertEquals($data->toArray(), $data->jsonSerialize());
    }

    public function testToString(): void
    {
        $data = new SalarySubsetData();
        $data->setStaffId('EMP006')->setNumericVal(1800.50);

        // __toString() 应该返回 JSON 字符串
        $expectedString = '{"staffId":"EMP006","numericVal":1800.5}';
        $this->assertEquals($expectedString, (string)$data);
    }

    public function testInheritedModelMethods(): void
    {
        $data = new SalarySubsetData();
        $data->setStaffId('EMP007');

        // 测试继承自Model的方法
        $this->assertInstanceOf(\JsonSerializable::class, $data);

        // 测试方法返回正确的数据
        $toArrayResult = $data->toArray();
        $this->assertEquals($data->jsonSerialize(), $toArrayResult);

        // 测试toString
        $stringResult = (string)$data;
        $this->assertNotEmpty($stringResult);
        $this->assertEquals(json_encode($toArrayResult, JSON_UNESCAPED_UNICODE), $stringResult);
    }

    public function testNullValues(): void
    {
        $data = new SalarySubsetData();

        // 测试所有getter在未设置时返回null
        $this->assertNull($data->getStaffId());
        $this->assertNull($data->getStartDate());
        $this->assertNull($data->getStopDate());
        $this->assertNull($data->getIsInvalid());
        $this->assertNull($data->getStatus());
        $this->assertNull($data->getItemName());
        $this->assertNull($data->getNumericVal());
        $this->assertNull($data->getNote());
        $this->assertNull($data->getCustomFields());
    }

    public function testBooleanValues(): void
    {
        $data = new SalarySubsetData();

        // 测试布尔值的设置和获取
        $data->setIsInvalid(true);
        $this->assertTrue($data->getIsInvalid());

        $data->setIsInvalid(false);
        $this->assertFalse($data->getIsInvalid());

        $data->setStatus(true);
        $this->assertTrue($data->getStatus());

        $data->setStatus(false);
        $this->assertFalse($data->getStatus());
    }
}
