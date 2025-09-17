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

use ITalentOpenSDK\Model\EmployeeSubsetData;
use ITalentOpenSDK\Tests\TestCase;

class EmployeeSubsetDataTest extends TestCase
{
    public function testUserIdAndUserOriginalIdMutualExclusion(): void
    {
        $data = new EmployeeSubsetData();

        // 设置UserId应该清空UserOriginalId
        $data->setUserId(12345);
        $this->assertEquals(12345, $data->getUserId());
        $this->assertNull($data->getUserOriginalId());

        // 设置UserOriginalId应该清空UserId
        $data->setUserOriginalId('EXT001');
        $this->assertEquals('EXT001', $data->getUserOriginalId());
        $this->assertNull($data->getUserId());

        // 再次设置UserId应该清空UserOriginalId
        $data->setUserId(67890);
        $this->assertEquals(67890, $data->getUserId());
        $this->assertNull($data->getUserOriginalId());
    }

    public function testFields(): void
    {
        $data = new EmployeeSubsetData();

        // 测试设置标准字段字典
        $fields = [
            'Name' => '张三',
            'Age' => 30,
            'Department' => '技术部',
        ];
        $data->setFields($fields);
        $this->assertEquals($fields, $data->getFields());

        // 测试添加单个标准字段
        $data->addField('Position', '高级工程师');
        $expected = array_merge($fields, ['Position' => '高级工程师']);
        $this->assertEquals($expected, $data->getFields());
    }

    public function testAddFieldToEmptyFields(): void
    {
        $data = new EmployeeSubsetData();

        // 在空的字段上添加
        $data->addField('FirstField', 'FirstValue');
        $this->assertEquals(['FirstField' => 'FirstValue'], $data->getFields());

        // 继续添加
        $data->addField('SecondField', 'SecondValue');
        $expected = [
            'FirstField' => 'FirstValue',
            'SecondField' => 'SecondValue',
        ];
        $this->assertEquals($expected, $data->getFields());
    }

    public function testSysProperties(): void
    {
        $data = new EmployeeSubsetData();

        // 测试设置系统属性字典
        $sysProperties = [
            'SysField1' => 'SysValue1',
            'SysField2' => 'SysValue2',
        ];
        $data->setSysProperties($sysProperties);
        $this->assertEquals($sysProperties, $data->getSysProperties());

        // 测试添加单个系统属性
        $data->addSysProperty('SysField3', 'SysValue3');
        $expected = array_merge($sysProperties, ['SysField3' => 'SysValue3']);
        $this->assertEquals($expected, $data->getSysProperties());
    }

    public function testAddSysPropertyToEmptyProperties(): void
    {
        $data = new EmployeeSubsetData();

        // 在空的系统属性上添加
        $data->addSysProperty('FirstSys', 'FirstSysValue');
        $this->assertEquals(['FirstSys' => 'FirstSysValue'], $data->getSysProperties());
    }

    public function testCustomProperties(): void
    {
        $data = new EmployeeSubsetData();

        // 测试设置自定义属性字典
        $customProperties = [
            'extone_600399_783312538' => '文本说明',
            'extyzzjyg_600399_1334600576' => '660006278',
        ];
        $data->setCustomProperties($customProperties);
        $this->assertEquals($customProperties, $data->getCustomProperties());

        // 测试添加单个自定义属性
        $data->addCustomProperty('custom_field', 'custom_value');
        $expected = array_merge($customProperties, ['custom_field' => 'custom_value']);
        $this->assertEquals($expected, $data->getCustomProperties());
    }

    public function testAddCustomPropertyToEmptyProperties(): void
    {
        $data = new EmployeeSubsetData();

        // 在空的自定义属性上添加
        $data->addCustomProperty('FirstCustom', 'FirstCustomValue');
        $this->assertEquals(['FirstCustom' => 'FirstCustomValue'], $data->getCustomProperties());
    }

    public function testObjectId(): void
    {
        $data = new EmployeeSubsetData();

        $objectId = '094ae643-2b5a-4e00-b99c-0e5aaf0e5c66';
        $data->setObjectId($objectId);
        $this->assertEquals($objectId, $data->getObjectId());
    }

    public function testEmptyFields(): void
    {
        $data = new EmployeeSubsetData();

        $emptyFields = ['Desc', 'Note', 'Comment'];
        $data->setEmptyFields($emptyFields);
        $this->assertEquals($emptyFields, $data->getEmptyFields());
    }

    public function testFluentInterface(): void
    {
        $data = new EmployeeSubsetData();

        // 测试链式调用
        $result = $data
            ->setUserId(12345)
            ->addField('Name', '李四')
            ->addCustomProperty('ext_field', 'ext_value')
            ->setObjectId('test-object-id');

        $this->assertInstanceOf(EmployeeSubsetData::class, $result);
        $this->assertSame($data, $result);

        // 验证设置的值
        $this->assertEquals(12345, $data->getUserId());
        $this->assertEquals(['Name' => '李四'], $data->getFields());
        $this->assertEquals(['ext_field' => 'ext_value'], $data->getCustomProperties());
        $this->assertEquals('test-object-id', $data->getObjectId());
    }

    public function testToArrayWithAllFields(): void
    {
        $data = new EmployeeSubsetData();
        $data
            ->setUserId(12345)
            ->setFields(['Name' => '王五', 'Age' => 25])
            ->setSysProperties(['SysField' => 'SysValue'])
            ->setCustomProperties(['CustomField' => 'CustomValue'])
            ->setObjectId('094ae643-2b5a-4e00-b99c-0e5aaf0e5c66')
            ->setEmptyFields(['OldField1', 'OldField2']);

        $result = $data->toArray();

        $expected = [
            'userId' => 12345,
            'fields' => ['Name' => '王五', 'Age' => 25],
            'sysProperties' => ['SysField' => 'SysValue'],
            'customProperties' => ['CustomField' => 'CustomValue'],
            'objectId' => '094ae643-2b5a-4e00-b99c-0e5aaf0e5c66',
            'emptyFields' => ['OldField1', 'OldField2'],
        ];

        $this->assertEquals($expected, $result);
    }

    public function testToArrayWithUserOriginalId(): void
    {
        $data = new EmployeeSubsetData();
        $data
            ->setUserOriginalId('EXT002')
            ->setFields(['Name' => '赵六']);

        $result = $data->toArray();

        $expected = [
            'userOriginalId' => 'EXT002',
            'fields' => ['Name' => '赵六'],
        ];

        $this->assertEquals($expected, $result);

        // 确保userId不在结果中
        $this->assertArrayNotHasKey('userId', $result);
    }

    public function testToArrayWithPartialFields(): void
    {
        $data = new EmployeeSubsetData();
        $data
            ->setUserId(54321)
            ->addField('Department', '人事部');

        $result = $data->toArray();

        $expected = [
            'userId' => 54321,
            'fields' => ['Department' => '人事部'],
        ];

        $this->assertEquals($expected, $result);

        // 确保null值不会出现在结果中
        $this->assertArrayNotHasKey('userOriginalId', $result);
        $this->assertArrayNotHasKey('sysProperties', $result);
        $this->assertArrayNotHasKey('customProperties', $result);
        $this->assertArrayNotHasKey('objectId', $result);
        $this->assertArrayNotHasKey('emptyFields', $result);
    }

    public function testToArrayWithEmptyData(): void
    {
        $data = new EmployeeSubsetData();
        $result = $data->toArray();

        $this->assertEquals([], $result);
    }

    public function testJsonSerializable(): void
    {
        $data = new EmployeeSubsetData();
        $data
            ->setUserId(99999)
            ->addField('Name', '测试用户');

        // 测试 json_encode 会自动调用 jsonSerialize()
        $jsonString = json_encode($data, JSON_UNESCAPED_UNICODE);
        $expectedJson = '{"userId":99999,"fields":{"Name":"测试用户"}}';
        $this->assertEquals($expectedJson, $jsonString);

        // 测试 jsonSerialize 方法直接调用
        $this->assertEquals($data->toArray(), $data->jsonSerialize());
    }

    public function testToString(): void
    {
        $data = new EmployeeSubsetData();
        $data->setUserOriginalId('STR001');

        // __toString() 应该返回 JSON 字符串
        $expectedString = '{"userOriginalId":"STR001"}';
        $this->assertEquals($expectedString, (string)$data);
    }

    public function testInheritedModelMethods(): void
    {
        $data = new EmployeeSubsetData();
        $data->setUserId(11111);

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
        $data = new EmployeeSubsetData();

        // 测试所有getter在未设置时返回null
        $this->assertNull($data->getUserId());
        $this->assertNull($data->getUserOriginalId());
        $this->assertNull($data->getFields());
        $this->assertNull($data->getSysProperties());
        $this->assertNull($data->getCustomProperties());
        $this->assertNull($data->getObjectId());
        $this->assertNull($data->getEmptyFields());
    }

    public function testCreateScenario(): void
    {
        // 创建场景：使用UserId和基本字段
        $data = new EmployeeSubsetData();
        $data
            ->setUserId(12345)
            ->setFields(['Name' => '新员工', 'Department' => '技术部'])
            ->setCustomProperties(['ext_hire_date' => '2023-01-01']);

        $result = $data->toArray();

        $this->assertArrayHasKey('userId', $result);
        $this->assertArrayHasKey('fields', $result);
        $this->assertArrayHasKey('customProperties', $result);
        $this->assertArrayNotHasKey('objectId', $result);
        $this->assertArrayNotHasKey('emptyFields', $result);
    }

    public function testUpdateScenario(): void
    {
        // 更新场景：使用ObjectId和清空字段
        $data = new EmployeeSubsetData();
        $data
            ->setObjectId('094ae643-2b5a-4e00-b99c-0e5aaf0e5c66')
            ->setFields(['Department' => '财务部'])
            ->setEmptyFields(['OldDepartment', 'TempField']);

        $result = $data->toArray();

        $this->assertArrayHasKey('objectId', $result);
        $this->assertArrayHasKey('fields', $result);
        $this->assertArrayHasKey('emptyFields', $result);
        $this->assertArrayNotHasKey('userId', $result);
        $this->assertArrayNotHasKey('userOriginalId', $result);
    }
}
