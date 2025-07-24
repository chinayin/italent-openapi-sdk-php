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

require_once __DIR__ . '/../vendor/autoload.php';

use ITalentOpenSDK\Model\SearchFilter;

// 创建搜索过滤器实例
$searchFilter = new SearchFilter();

// 设置基本搜索参数
$searchFilter
    ->setStartTime('2021-01-01T00:00:00')
    ->setStopTime('2021-01-02T00:00:00')
    ->setTimeWindowQueryType('1') // 1修改时间、2业务修改时间
    ->setCapacity(100)
    ->setIsWithDeleted(false);

// 添加排序条件
$searchFilter
    ->addSort('Name', '1')  // 按名称升序
    ->addSort('Age', '2');  // 按年龄降序

// 或者直接设置排序数组
$searchFilter->setSort([
    'Name' => '1',
    'Age' => '2',
]);

// 添加自定义字段查询条件
$searchFilter->addExtQuery(
    'extExtQueryFloat_127666_832132060',
    5,
    ['1']
);

// 设置查询字段列表
$searchFilter->setColumns(['Name', 'OId', 'Code', 'Status']);

// 设置额外的自定义参数
$searchFilter
    ->setExtraParam('customField1', 'customValue1')
    ->setExtraParam('customField2', ['array', 'value']);

// 转换为数组，用于传递给 post() 方法
$searchParams = $searchFilter->toArray();

echo "搜索参数:\n";
echo json_encode($searchParams, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// 模拟 scroll 查询的使用场景
echo "\n\n=== Scroll 查询示例 ===\n";

// 第一次查询
$firstQuery = new SearchFilter();
$firstQuery
    ->setStartTime('2021-01-01T00:00:00')
    ->setStopTime('2021-01-02T00:00:00')
    ->setCapacity(50);

echo "第一次查询参数:\n";
echo json_encode($firstQuery->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// 后续查询（使用 scrollId）
$nextQuery = new SearchFilter();
$nextQuery
    ->setScrollId('DXF1ZXJ5QW5kRmV0Y2gBAAAAAAVrsaUWdnVycEd3OEFRRm02aEpHRFZQZ2htdw==')
    ->setCapacity(50);

echo "\n后续查询参数:\n";
echo json_encode($nextQuery->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
