# ITalent OpenAPI SDK for PHP

[![Latest Stable Version](https://poser.pugx.org/chinayin/italent-openapi-sdk/v/stable)](https://packagist.org/packages/chinayin/italent-openapi-sdk)
[![Total Downloads](https://poser.pugx.org/chinayin/italent-openapi-sdk/downloads)](https://packagist.org/packages/chinayin/italent-openapi-sdk)
[![License](https://poser.pugx.org/chinayin/italent-openapi-sdk/license)](https://packagist.org/packages/chinayin/italent-openapi-sdk)

北森人才管理软件 OpenAPI SDK for PHP

## 安装

```bash
composer require chinayin/italent-openapi-sdk
```

## 环境要求

- PHP >= 7.4
- ext-json, ext-zlib

## 快速开始

### 环境变量配置（非必须）

```bash
export SDK_APP_KEY=your_app_key
export SDK_APP_SECRET=your_app_secret
```

### API调用方式

SDK提供两种API调用方式：
**建议：** 优先使用抽象API类，对于未覆盖的API使用通用OpenAPI调用。

#### 1. 抽象API类调用（推荐）

对于常用的API，SDK已经抽象出对应的API类，提供类型安全和便捷的方法调用：

```php
<?php
use ITalentOpenSDK\Model\ScrollResult;

// 获取Employee API实例
$employee = $sdk->get('employee');

// 根据邮箱获取用户ID
$userId = $employee->getUserIDByEmail('user@example.com');

// 批量根据工号获取用户ID
$userIds = $employee->getUserIDsByJobNumbers(['JOB001', 'JOB002']);

// 批量根据手机号获取用户ID
$userIds = $employee->getUserIDsByMobiles(['13800138000', '13900139000']);

// 滚动查询员工变动信息
$searchFilter = (new SearchFilter())
    ->setStartTime(date('Y-m-d', strtotime('-7 days')))
    ->setStopTime(date('Y-m-d'))
    ->setCapacity(2);
$result = $employee->getByTimeWindow($searchFilter);

echo "数据条数: " . count($result->items()) . "\n";
echo "是否有更多: " . ($result->hasMore() ? '是' : '否') . "\n";
echo "滚动ID: " . $result->scrollId() . "\n";
echo "总数: " . $result->total() . "\n";

// 分页循环获取所有数据
$scrollId = '';
$allEmployees = [];
$searchFilter = (new SearchFilter())
    ->setStartTime(date('Y-m-d', strtotime('-7 days')))
    ->setStopTime(date('Y-m-d'))
    ->setCapacity(2);
do {

    $result = $employee->getByTimeWindow($searchFilter->setScrollId($scrollId));

    $allEmployees = array_merge($allEmployees, $result->items());
    $scrollId = $result->scrollId();

} while ($result->hasMore());

// 根据用户ID获取员工基本信息
$searchFilter = (new SearchFilter())->setColumns(['name', 'email']);
$employeeInfo = $employee->getBasicInfoByIds([123, 456], $searchFilter);
foreach ($employeeInfo->items() as $emp) {
    echo "员工: {$emp['name']} - {$emp['email']}\n";
}
```

#### 2. 通用OpenAPI调用

对于未抽象的API或需要更灵活调用的场景，可以使用通用的OpenAPI方法：

```php
<?php
// 获取OpenAPI实例
$openapi = $sdk->get('openapi');

// POST 请求 - 滚动查询员工信息
$result = $openapi->post('TenantBaseExternal/api/v5/Employee/GetByTimeWindow', [
    'startTime' => date('Y-m-d', strtotime('-30 days')),
    'stopTime' => date('Y-m-d'),
    'timeWindowQueryType' => 1,
    'scrollId' => '',
]);

// 手动处理响应
$employees = $result['data'] ?? [];
$scrollId = $result['scrollId'] ?? '';
$hasMore = !empty($employees) && !empty($scrollId);

echo "获取到 " . count($employees) . " 条员工记录\n";
echo "是否有更多: " . ($hasMore ? '是' : '否') . "\n";

// GET 请求示例
$result = $openapi->get('TenantBaseExternal/api/v5/OrganizationType/GetOrganizationTypeByName', [
    'name' => '部门'
]);
```

## 错误处理

```php
try {
    $result = $openapi->post('some/endpoint', $params);
} catch (\ITalentOpenSDK\Exception\ITalentException $e) {
    echo 'API错误: ' . $e->getMessage();
    if ($response = $e->getResponse()) {
        echo '详情: ' . json_encode($response);
    }
} catch (\Exception $e) {
    echo '系统错误: ' . $e->getMessage();
}
```
