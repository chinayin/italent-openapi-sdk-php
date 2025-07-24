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

namespace ITalentOpenSDK\Http;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use ITalentOpenSDK\Auth\TokenStrategyInterface;
use ITalentOpenSDK\Constants;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class ClientFactory
{
    public static function create(LoggerInterface $logger, $token = null, array $config = []): Client
    {
        $stack = HandlerStack::create();

        // 日志中间件配置
        $logLevel = $config['log']['level'] ?? LogLevel::INFO;
        $logFormat = strtolower($logLevel) === LogLevel::DEBUG ? MessageFormatter::DEBUG : MessageFormatter::CLF;
        $stack->push(Middleware::log($logger, $logLevel, $logFormat));

        // ua
        $stack->push(Middleware::useragent());

        // Token认证中间件（如果提供了token）
        if ($token instanceof TokenStrategyInterface) {
            $stack->push(Middleware::auth($token));
        }

        // 重试
        $stack->push(Middleware::retry($logger));

        // 响应处理
        $stack->push(Middleware::response());

        // 构建客户端配置 - 使用白名单方式确保安全
        $clientConfig = [
            'base_uri' => Constants::SDK_BASE_URI,
            // handler不允许被覆盖，保护中间件栈
            'handler' => $stack,
        ];

        // 只允许白名单中的HTTP配置选项
        $httpConfig = $config['http'] ?? [];
        foreach (self::getAllowedHttpOptions() as $option) {
            if (isset($httpConfig[$option])) {
                $clientConfig[$option] = $httpConfig[$option];
            }
        }

        return new Client($clientConfig);
    }

    /**
     * 获取允许的HTTP配置选项列表
     *
     * @return array
     */
    private static function getAllowedHttpOptions(): array
    {
        return [
            'base_uri', 'proxy', 'debug',
            'timeout', 'connect_timeout', 'read_timeout',
        ];
    }
}
