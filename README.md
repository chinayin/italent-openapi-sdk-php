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

### 环境变量配置

```bash
export SDK_APP_KEY=your_app_key
export SDK_APP_SECRET=your_app_secret
```

### OpenAPI 调用

```php
<?php
require_once 'vendor/autoload.php';

use ITalentOpenSDK\ITalentSDK;

$sdk = new ITalentSDK([
    'app_key' => getenv('SDK_APP_KEY'),
    'app_secret' => getenv('SDK_APP_SECRET'),
    'cache' => ['path' => __DIR__ . '/runtime/cache'],
    'log' => ['file' => __DIR__ . '/runtime/log/sdk.log', 'level' => 'info'],
]);

$openapi = $sdk->get('openapi');

// POST 请求
$result = $openapi->post('TenantBaseExternal/api/v5/Organization/GetByTimeWindow', [
    'startTime' => date('Y-m-d', strtotime('-30 days')),
    'stopTime' => date('Y-m-d'),
    'timeWindowQueryType' => 1,
    'scrollId' => '',
]);

// GET 请求
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
