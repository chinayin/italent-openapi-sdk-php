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

use ITalentOpenAPI\Exception\ITalentException;
use ITalentOpenSDK\Auth\TokenManagerFactory;
use ITalentOpenSDK\Http\ClientFactory;
use ITalentOpenSDK\Http\HttpClient;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * 北森 OpenAPI SDK TOKEN 示例
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
if (!is_dir("$runtimeDir/log")) {
    mkdir("$runtimeDir/log", 0755, true);
}

// 创建一个简单的logger
$logger = new Logger('token_usage');
$logger->pushHandler(new StreamHandler("$runtimeDir/log/token_usage.log", Logger::DEBUG));

// 创建缓存和HTTP客户端
$cache = new FilesystemAdapter('token_usage', 0, "$runtimeDir/cache");
$httpClient = new HttpClient(ClientFactory::create($logger));

// 创建Token缓存适配器
$token = TokenManagerFactory::createFromConfig([
    'app_key' => $appKey,
    'app_secret' => $appSecret,
], $cache, $httpClient);

echo "=== 北森 OpenAPI SDK TOKEN 示例 ===\n\n";

try {
    // 1. Token 管理
    echo "1. Token 管理\n";
    echo "====================\n";

    // 获取访问令牌（新架构：统一缓存，避免双重存储）
    $startTime = microtime(true);
    $accessToken = $token->getAccessToken();
    $getTime = (microtime(true) - $startTime) * 1000;

    echo "✓ 访问令牌: " . substr($accessToken, 0, 30) . "...\n";
    echo "✓ 获取耗时: " . round($getTime, 2) . "ms\n";
    echo "✓ 获取方式: " . ($getTime > 100 ? 'HTTPS请求' : '缓存读取') . "\n";

    // 新架构增强的状态监控功能
    echo "\n令牌状态监控:\n";
    $remaining = $token->getTokenRemainingTime();
    if ($remaining !== null) {
        $hours = floor($remaining / 3600);
        $minutes = floor(($remaining % 3600) / 60);
        $seconds = $remaining % 60;

        echo "  ├─ 剩余时间: {$hours}h {$minutes}m {$seconds}s ({$remaining}秒)\n";
        echo "  ├─ 过期时间: " . date('c', time() + $remaining) . "\n";
        echo "  ├─ 剩余百分比: " . round($remaining / 7200 * 100, 1) . "%\n";
    } else {
        echo "  ├─ 剩余时间: 无法获取\n";
    }

    echo "  ├─ 令牌状态: " . ($token->isTokenExpired() ? '已过期' : '有效') . "\n";
    echo "  └─ 刷新建议: " . ($token->shouldRefresh() ? '建议刷新' : '无需刷新') . "\n";

    // 2. 缓存性能展示
    echo "\n2. 缓存性能测试\n";
    echo "================\n";
    $cacheTests = 10;
    $cacheTimes = [];

    echo "执行 {$cacheTests} 次缓存获取测试:\n";
    for ($i = 1; $i <= $cacheTests; $i++) {
        $startTime = microtime(true);
        $cachedToken = $token->getAccessToken();
        $cacheTime = (microtime(true) - $startTime) * 1000;
        $cacheTimes[] = $cacheTime;

        echo "  第 {$i} 次: " . round($cacheTime, 3) . "ms\n";
    }

    $avgCacheTime = array_sum($cacheTimes) / count($cacheTimes);
    $minCacheTime = min($cacheTimes);
    $maxCacheTime = max($cacheTimes);
    $stdDev = sqrt(array_sum(array_map(fn ($x) => ($x - $avgCacheTime) ** 2, $cacheTimes)) / count($cacheTimes));

    echo "\n缓存性能统计:\n";
    echo "  ├─ 平均耗时: " . round($avgCacheTime, 3) . "ms\n";
    echo "  ├─ 最快读取: " . round($minCacheTime, 3) . "ms\n";
    echo "  ├─ 最慢读取: " . round($maxCacheTime, 3) . "ms\n";
    echo "  ├─ 标准差: " . round($stdDev, 3) . "ms\n";
    echo "  ├─ 性能提升: " . round(($getTime - $avgCacheTime) / $getTime * 100, 1) . "%\n";
    echo "  └─ 令牌一致性: " . ($accessToken === $cachedToken ? '✓ 完全一致' : '✗ 不一致') . "\n";

    // 3. 智能令牌刷新演示
    echo "\n3. 智能令牌刷新演示\n";
    echo "====================\n";

    if ($token->shouldRefresh()) {
        echo "检测到令牌即将过期，执行刷新...\n";
        $startTime = microtime(true);
        $refreshedToken = $token->refreshAccessToken();
        $refreshTime = (microtime(true) - $startTime) * 1000;

        echo "✓ 智能刷新完成\n";
        echo "  ├─ 刷新耗时: " . round($refreshTime, 2) . "ms\n";
        echo "  ├─ 新令牌: " . substr($refreshedToken, 0, 30) . "...\n";
        echo "  └─ 令牌已更新: " . ($accessToken !== $refreshedToken ? '是' : '否') . "\n";
    } else {
        echo "令牌仍然有效，无需刷新\n";
        $refreshThreshold = 300; // 默认5分钟阈值
        if ($remaining !== null) {
            $timeToRefresh = max(0, $remaining - $refreshThreshold);
            echo "  └─ 距离自动刷新: " . $timeToRefresh . " 秒\n";
        }
    }

    // 4. 强制刷新演示
    echo "\n4. 强制刷新演示\n";
    echo "================\n";
    echo "执行强制刷新（无论令牌是否过期）...\n";
    $startTime = microtime(true);
    $forceRefreshToken = $token->getAccessToken(true);
    $forceRefreshTime = (microtime(true) - $startTime) * 1000;

    echo "✓ 强制刷新完成\n";
    echo "  ├─ 刷新耗时: " . round($forceRefreshTime, 2) . "ms\n";
    echo "  ├─ 新令牌: " . substr($forceRefreshToken, 0, 30) . "...\n";
    echo "  ├─ 令牌更新: " . ($accessToken !== $forceRefreshToken ? '✅ 已更新' : '❌ 未更新') . "\n";
    echo "  └─ 与智能刷新对比: " . (isset($refreshTime) ? round($forceRefreshTime / $refreshTime, 2) . 'x' : 'N/A') . "\n";

    // 5. 完整生命周期管理
    echo "\n5. 完整生命周期管理\n";
    echo "====================\n";

    // 显示当前令牌详细信息
    echo "5.1 当前令牌详细信息\n";
    echo "--------------------\n";
    $currentRemaining = $token->getTokenRemainingTime();
    if ($currentRemaining !== null) {
        $hours = floor($currentRemaining / 3600);
        $minutes = floor(($currentRemaining % 3600) / 60);
        $seconds = $currentRemaining % 60;

        echo "📋 令牌详细信息:\n";
        echo "  ├─ 格式化时间: {$hours}小时 {$minutes}分钟 {$seconds}秒\n";
        echo "  ├─ 预计过期: " . date('c', time() + $currentRemaining) . "\n";
        echo "  ├─ 健康状态: " . ($currentRemaining > 600 ? '🟢 健康' : ($currentRemaining > 300 ? '🟡 注意' : '🔴 警告')) . "\n";
        echo "  └─ 建议操作: " . ($currentRemaining > 600 ? '继续使用' : '准备刷新') . "\n";
    }

    // 清除和重建演示
    echo "\n5.2 清除和重建演示\n";
    echo "--------------------\n";
    echo "🗑️  正在清除所有令牌缓存...\n";
    $token->clearTokens();
    echo "✅ 令牌缓存已清除\n";

    // 验证清除效果
    $isCleared = $token->isTokenExpired();
    echo "🔍 验证清除效果: " . ($isCleared ? '✅ 已清除' : '❌ 未清除') . "\n";

    // 重新获取令牌
    echo "\n🔄 重新获取令牌...\n";
    $startTime = microtime(true);
    $newToken = $token->getAccessToken();
    $newTokenTime = (microtime(true) - $startTime) * 1000;

    echo "✅ 新令牌获取完成\n";
    echo "  ├─ 新令牌: " . substr($newToken, 0, 30) . "...\n";
    echo "  ├─ 获取耗时: " . round($newTokenTime, 2) . "ms\n";
    echo "  ├─ 与之前不同: " . ($forceRefreshToken !== $newToken ? '✅ 是' : '❌ 否') . "\n";
    echo "  └─ 重建效率: " . round($newTokenTime / $getTime * 100, 1) . "% (相对首次)\n";

    // 6. 高级管理器功能演示
    echo "\n6. 高级管理器功能演示\n";
    echo "======================\n";

    // 创建直接管理器实例
    $cache = new FilesystemAdapter('advanced_token_usage', 0, "$runtimeDir/cache");
    $advancedManager = TokenManagerFactory::createITalentTokenManager($appKey, $appSecret, $cache, $httpClient);

    // 自定义刷新策略
    echo "6.1 自定义刷新策略\n";
    echo "------------------\n";
    $customThreshold = 600; // 10分钟
    $advancedManager->setRefreshThreshold($customThreshold);
    echo "✅ 设置自定义刷新阈值: {$customThreshold}秒(10分钟)\n";

    $advancedToken = $advancedManager->getAccessToken();
    $advancedShouldRefresh = $advancedManager->shouldRefresh();
    echo "🔍 自定义策略检查: " . ($advancedShouldRefresh ? '需要刷新' : '无需刷新') . "\n";

    // TokenData 对象详细信息
    echo "\n6.2 TokenData 对象分析\n";
    echo "----------------------\n";
    $tokenData = $advancedManager->getTokenData();
    if ($tokenData) {
        echo "📊 TokenData 对象详细信息:\n";
        echo "  ├─ 访问令牌: " . substr($tokenData->getAccessToken(), 0, 25) . "...\n";
        echo "  ├─ 刷新令牌: " . substr($tokenData->getRefreshToken(), 0, 25) . "...\n";
        echo "  ├─ 令牌类型: " . $tokenData->getTokenType() . "\n";
        echo "  ├─ 有效期: " . $tokenData->getExpiresIn() . " 秒\n";
        echo "  ├─ 过期时间戳: " . $tokenData->getExpiresAt() . "(" . date('c', $tokenData->getExpiresAt()) . ")\n";
        echo "  ├─ 剩余时间: " . $tokenData->getRemainingTime() . " 秒\n";
        echo "  └─ 是否过期: " . ($tokenData->isExpired() ? '是' : '否') . "\n";

        // 数组格式展示
        $tokenArray = $tokenData->toArray();
        echo "\n📋 数组格式预览:\n";
        foreach ($tokenArray as $key => $value) {
            $displayValue = is_string($value) && strlen($value) > 30 ? substr($value, 0, 25) . '...' : $value;
            echo "  {$key}: {$displayValue}\n";
        }
    } else {
        echo "❌ 无法获取 TokenData 对象\n";
    }

    // 7. 性能基准和总结
    echo "\n7. 性能基准和总结\n";
    echo "==================\n";

    echo "📊 本次会话性能统计:\n";
    echo "  ├─ 首次获取: " . round($getTime, 2) . "ms\n";
    echo "  ├─ 平均缓存: " . round($avgCacheTime, 3) . "ms\n";
    echo "  ├─ 强制刷新: " . round($forceRefreshTime, 2) . "ms\n";
    echo "  ├─ 重新获取: " . round($newTokenTime, 2) . "ms\n";
    echo "  └─ 缓存效率: " . round(($getTime - $avgCacheTime) / $getTime * 100, 1) . "%\n";

    // 性能等级评估
    if ($avgCacheTime < 0.1) {
        $grade = "优秀 (A+)";
        $emoji = "🏆";
    } elseif ($avgCacheTime < 0.5) {
        $grade = "良好 (A)";
        $emoji = "🥇";
    } elseif ($avgCacheTime < 1.0) {
        $grade = "一般 (B)";
        $emoji = "🥈";
    } else {
        $grade = "需要优化 (C)";
        $emoji = "🥉";
    }

    echo "\n{$emoji} 性能等级评估: {$grade}\n";

    echo "\n=== Token 管理示例完成 ===\n";
    echo "🎉 新架构核心优势:\n";
    echo "✅ 统一缓存管理 - 避免双重存储，提升性能\n";
    echo "✅ 智能刷新策略 - 自动检测，无缝刷新\n";
    echo "✅ 详细状态监控 - 实时状态，精确控制\n";
    echo "✅ 高性能缓存 - 毫秒级响应，显著提升\n";
    echo "✅ 灵活配置管理 - 自定义策略，适应需求\n";
    echo "✅ 完整生命周期 - 从创建到销毁的全程管理\n";

} catch (ITalentException $e) {
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
