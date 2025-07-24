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

use ITalentOpenSDK\ITalentSDK;

/**
 * 北森 OpenAPI 接口调用示例
 * 参照 OpenApiTest 文件，提供简单的查询方法
 */

// 从环境变量获取敏感配置
$appKey = getenv('SDK_APP_KEY') ?: $_ENV['SDK_APP_KEY'] ?? null;
$appSecret = getenv('SDK_APP_SECRET') ?: $_ENV['SDK_APP_SECRET'] ?? null;

if (!$appKey || !$appSecret) {
    echo "❌ 错误: 请设置环境变量 SDK_APP_KEY 和 SDK_APP_SECRET\n";
    echo "示例:\n";
    echo "export SDK_APP_KEY=your_app_key\n";
    echo "export SDK_APP_SECRET=your_app_secret\n";
    exit(1);
}

// 创建运行时目录
$runtimeDir = __DIR__ . '/../runtime';
if (!is_dir($runtimeDir)) {
    mkdir($runtimeDir, 0755, true);
}

// 初始化 ITalentSDK
$config = [
    'app_key' => $appKey,
    'app_secret' => $appSecret,
    'cache' => [
        'path' => $runtimeDir . '/cache',
    ],
    'log' => [
        'file' => $runtimeDir . '/log/openapi_usage.log',
        'level' => 'debug',
    ],
];

$sdk = new ITalentSDK($config);
$openapi = $sdk->get('openapi');

echo "=== 北森 OpenAPI 接口调用示例 ===\n\n";

try {
    // 验证 SDK 和服务
    echo "1. SDK 初始化验证\n";
    echo "==================\n";
    echo "✓ ITalentSDK 初始化成功\n";
    echo "✓ OpenApi 服务类型: " . get_class($openapi) . "\n\n";

    // 示例1: 获取组织信息（按时间窗口）
    echo "2. 获取组织信息（按时间窗口）\n";
    echo "============================\n";

    $uri = 'TenantBaseExternal/api/v5/Organization/GetByTimeWindow';
    $query = [
        'startTime' => date('Y-m-d', strtotime('-30 days')),
        'stopTime' => date('Y-m-d'),
        'timeWindowQueryType' => 1,
        'scrollId' => "",
    ];

    echo "请求URI: {$uri}\n";
    echo "请求参数: " . json_encode($query, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

    $startTime = microtime(true);
    $result = $openapi->post($uri, $query);
    $apiTime = (microtime(true) - $startTime) * 1000;

    echo "✓ 请求成功 (耗时: " . round($apiTime, 2) . "ms)\n";
    echo "✓ 响应类型: " . gettype($result) . "\n";

    if (is_array($result)) {
        echo "✓ 响应字段: " . implode(', ', array_keys($result)) . "\n";

        if (isset($result['success'])) {
            echo "✓ API状态: " . ($result['success'] ? '成功' : '失败') . "\n";
        }

        if (isset($result['data']) && is_array($result['data'])) {
            echo "✓ 数据条数: " . count($result['data']) . "\n";

            // 显示第一条数据的结构（如果存在）
            if (!empty($result['data']) && is_array($result['data'][0])) {
                echo "✓ 数据字段: " . implode(', ', array_keys($result['data'][0])) . "\n";
            }
        }

        if (isset($result['message'])) {
            echo "✓ 响应消息: " . $result['message'] . "\n";
        }
    }

    echo "\n完整响应:\n";
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

    // 示例2: 简单的GET请求示例
    echo "3. 简单的GET请求示例\n";
    echo "====================\n";

    $getUri = 'TenantBaseExternal/api/v5/OrganizationType/GetOrganizationTypeByName';
    $getQuery = ['name' => '部门'];

    echo "请求URI: {$getUri}\n";
    echo "查询参数: " . json_encode($getQuery, JSON_UNESCAPED_UNICODE) . "\n";

    try {
        $startTime = microtime(true);
        $getResult = $openapi->get($getUri, $getQuery);
        $getTime = (microtime(true) - $startTime) * 1000;

        echo "✓ GET请求成功 (耗时: " . round($getTime, 2) . "ms)\n";
        echo "✓ 响应: " . json_encode($getResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

    } catch (Exception $e) {
        echo "✗ GET请求失败: " . $e->getMessage() . "\n\n";
    }

    // 示例3: 自定义查询方法
    echo "4. 自定义查询方法\n";
    echo "================\n";

    // 定义一个简单的查询函数
    $queryOrganization = function ($days = 7) use ($openapi) {
        $uri = 'TenantBaseExternal/api/v5/Organization/GetByTimeWindow';
        $params = [
            'startTime' => date('Y-m-d', strtotime("-{$days} days")),
            'stopTime' => date('Y-m-d'),
            'timeWindowQueryType' => 1,
            'scrollId' => "",
        ];

        echo "查询最近 {$days} 天的组织变更...\n";
        $startTime = microtime(true);
        $result = $openapi->post($uri, $params);
        $queryTime = (microtime(true) - $startTime) * 1000;

        echo "✓ 查询完成 (耗时: " . round($queryTime, 2) . "ms)\n";

        if (isset($result['data']) && is_array($result['data'])) {
            echo "✓ 找到 " . count($result['data']) . " 条记录\n";
        }

        return $result;
    };

    // 使用自定义查询方法
    $result7Days = $queryOrganization(7);
    $result30Days = $queryOrganization(30);

    echo "\n";

    // 示例4: 批量查询示例
    echo "5. 批量查询示例\n";
    echo "===============\n";

    $batchQueries = [
        [
            'name' => '查询组织类型',
            'method' => 'get',
            'uri' => 'TenantBaseExternal/api/v5/OrganizationType/GetOrganizationTypeByName',
            'params' => ['name' => '部门'],
        ],
        [
            'name' => '查询最近组织变更',
            'method' => 'post',
            'uri' => 'TenantBaseExternal/api/v5/Organization/GetByTimeWindow',
            'params' => [
                'startTime' => date('Y-m-d', strtotime('-7 days')),
                'stopTime' => date('Y-m-d'),
                'timeWindowQueryType' => 1,
                'scrollId' => "",
            ],
        ],
    ];

    foreach ($batchQueries as $index => $queryConfig) {
        echo "5." . ($index + 1) . " {$queryConfig['name']}\n";
        echo str_repeat('-', 20) . "\n";

        try {
            $startTime = microtime(true);

            if ($queryConfig['method'] === 'get') {
                $batchResult = $openapi->get($queryConfig['uri'], $queryConfig['params']);
            } else {
                $batchResult = $openapi->post($queryConfig['uri'], $queryConfig['params']);
            }

            $batchTime = (microtime(true) - $startTime) * 1000;

            echo "✓ {$queryConfig['name']} 成功 (耗时: " . round($batchTime, 2) . "ms)\n";

            if (isset($batchResult['data'])) {
                $dataCount = is_array($batchResult['data']) ? count($batchResult['data']) : 1;
                echo "✓ 返回数据: {$dataCount} 条\n";
            }

        } catch (Exception $e) {
            echo "✗ {$queryConfig['name']} 失败: " . $e->getMessage() . "\n";
        }

        echo "\n";
    }

    echo "=== OpenAPI 示例执行完成 ===\n";
    echo "🎉 核心特性:\n";
    echo "✅ 使用 ITalentSDK 统一管理\n";
    echo "✅ 自动处理认证和Token管理\n";
    echo "✅ 支持GET和POST请求\n";
    echo "✅ 提供简单的查询方法\n";
    echo "✅ 支持批量查询操作\n";
    echo "✅ 完整的错误处理机制\n";

} catch (\ITalentOpenSDK\Exception\ITalentException $e) {
    echo "❌ 北森 API 错误: " . $e->getMessage() . "\n";
    $response = $e->getResponse();
    if ($response) {
        echo "错误详情: " . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    }
} catch (Exception $e) {
    echo "❌ 系统错误: " . $e->getMessage() . "\n";
    echo "错误位置: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "错误堆栈: " . $e->getTraceAsString() . "\n";
}
