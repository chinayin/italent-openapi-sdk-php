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

use ITalentOpenSDK\Model\OperationResult;
use ITalentOpenSDK\Tests\TestCase;

class OperationResultTest extends TestCase
{
    public function testFromArrayWithSuccessResult(): void
    {
        $data = [
            'code' => '200',
            'message' => '全部新增成功',
            'data' => null,
        ];

        $result = OperationResult::fromArray($data);

        $this->assertTrue($result->isSuccess());
        $this->assertEquals('200', $result->getCode());
        $this->assertEquals('全部新增成功', $result->getMessage());
        $this->assertNull($result->getData());
    }

    public function testFromArrayWithFailedResult(): void
    {
        $data = [
            'code' => '417',
            'message' => '全部新增失败',
            'data' => [
                'presetSalarySubsetCode' => 'PresetSalarySubset1',
                'models' => [
                    [
                        'errorMessage' => '字段"StaffID"的值"123123"不存在',
                        'data' => [
                            'customFields' => [
                                'extone_600399_783312538' => '文本说明',
                                'extyzzjyg_600399_1334600576' => '660006278',
                            ],
                            'staffId' => '123123',
                            'startDate' => '2020/01/01',
                            'isInvalid' => false,
                            'status' => true,
                            'stopDate' => '2020/01/02',
                            'itemName' => null,
                            'numericVal' => 1000.0,
                            'note' => '备注信息',
                        ],
                    ],
                ],
            ],
        ];

        $result = OperationResult::fromArray($data);

        $this->assertFalse($result->isSuccess());
        $this->assertEquals('417', $result->getCode());
        $this->assertEquals('全部新增失败', $result->getMessage());

        $errorModels = $result->getData()['models'] ?? [];
        $this->assertCount(1, $errorModels);
        $this->assertEquals('字段"StaffID"的值"123123"不存在', $errorModels[0]['errorMessage']);
        $this->assertEquals('123123', $errorModels[0]['data']['staffId']);
    }

    public function testFromArrayWithMissingFields(): void
    {
        $data = [];
        $result = OperationResult::fromArray($data);

        $this->assertFalse($result->isSuccess());
        $this->assertEquals('', $result->getCode());
        $this->assertEquals('', $result->getMessage());
        $this->assertNull($result->getData());
    }

    public function testFromArrayWithPartialData(): void
    {
        $data = [
            'code' => '500',
            'message' => '服务器错误',
        ];
        $result = OperationResult::fromArray($data);

        $this->assertFalse($result->isSuccess());
        $this->assertEquals('500', $result->getCode());
        $this->assertEquals('服务器错误', $result->getMessage());
        $this->assertNull($result->getData());
    }

    public function testFromArrayWithDataArray(): void
    {
        $data = [
            'code' => '200',
            'message' => '操作成功',
            'data' => ['result' => 'success', 'count' => 5],
        ];
        $result = OperationResult::fromArray($data);

        $this->assertTrue($result->isSuccess());
        $this->assertEquals(['result' => 'success', 'count' => 5], $result->getData());
    }

    public function testSuccessPropertyLogic(): void
    {
        // 测试成功情况 - 只有code为'200'才算成功
        $successData = ['code' => '200', 'message' => 'OK', 'data' => null];
        $successResult = OperationResult::fromArray($successData);
        $this->assertTrue($successResult->isSuccess());

        // 测试失败情况 - 其他code都算失败
        $failedCodes = ['417', '500', '404', '0', '201'];
        foreach ($failedCodes as $code) {
            $failedData = ['code' => $code, 'message' => 'Error', 'data' => []];
            $failedResult = OperationResult::fromArray($failedData);
            $this->assertFalse($failedResult->isSuccess(), "Code {$code} should be considered as failure");
        }
    }

    public function testToArray(): void
    {
        $originalData = [
            'code' => '200',
            'message' => '操作成功',
            'data' => ['key' => 'value'],
        ];

        $result = OperationResult::fromArray($originalData);
        $arrayResult = $result->toArray();

        $expectedArray = [
            'success' => true,
            'code' => '200',
            'message' => '操作成功',
            'data' => ['key' => 'value'],
        ];

        $this->assertEquals($expectedArray, $arrayResult);
    }

    public function testJsonSerializable(): void
    {
        $data = [
            'code' => '200',
            'message' => '操作成功',
            'data' => null,
        ];
        $result = OperationResult::fromArray($data);

        // 测试 json_encode 会自动调用 jsonSerialize()
        $jsonString = json_encode($result, JSON_UNESCAPED_UNICODE);
        $expectedJson = '{"success":true,"code":"200","message":"操作成功","data":null}';
        $this->assertEquals($expectedJson, $jsonString);

        // 测试 jsonSerialize 方法直接调用
        $this->assertEquals($result->toArray(), $result->jsonSerialize());
    }

    public function testToString(): void
    {
        $data = [
            'code' => '200',
            'message' => '操作成功',
            'data' => null,
        ];
        $result = OperationResult::fromArray($data);

        // __toString() 应该返回 JSON 字符串
        $expectedString = '{"success":true,"code":"200","message":"操作成功","data":null}';
        $this->assertEquals($expectedString, (string)$result);
    }

    public function testGetters(): void
    {
        $data = [
            'code' => '417',
            'message' => '业务错误',
            'data' => ['error' => 'details'],
        ];
        $result = OperationResult::fromArray($data);

        $this->assertFalse($result->isSuccess());
        $this->assertEquals('417', $result->getCode());
        $this->assertEquals('业务错误', $result->getMessage());
        $this->assertEquals(['error' => 'details'], $result->getData());
    }

    public function testInheritedModelMethods(): void
    {
        $data = [
            'code' => '200',
            'message' => '成功',
            'data' => null,
        ];
        $result = OperationResult::fromArray($data);

        // 测试继承自Model的方法
        $this->assertInstanceOf(\JsonSerializable::class, $result);

        // 测试方法返回正确的数据
        $toArrayResult = $result->toArray();
        $this->assertEquals($result->jsonSerialize(), $toArrayResult);

        // 测试toString
        $stringResult = (string)$result;
        $this->assertNotEmpty($stringResult);
        $this->assertEquals(json_encode($toArrayResult, JSON_UNESCAPED_UNICODE), $stringResult);
    }
}
