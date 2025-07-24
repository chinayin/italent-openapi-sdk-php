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

namespace ITalentOpenSDK\Auth;

use ITalentOpenSDK\Http\HttpClientInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Token 管理器工厂
 * 使用工厂模式创建不同类型的 Token 管理器
 */
class TokenManagerFactory
{
    /**
     * 创建北森 Token 管理器
     */
    public static function createITalentTokenManager(
        string $appKey,
        string $appSecret,
        CacheItemPoolInterface $cache,
        HttpClientInterface $httpClient
    ): ITalentTokenManager {
        $manager = new ITalentTokenManager();
        $manager->setAppKey($appKey);
        $manager->setAppSecret($appSecret);
        $manager->setCache($cache);
        $manager->setHttpClient($httpClient);

        return $manager;
    }

    /**
     * 根据配置创建 Token 管理器
     */
    public static function createFromConfig(
        array $config,
        CacheItemPoolInterface $cache,
        HttpClientInterface $httpClient
    ): TokenStrategyInterface {
        $type = $config['type'] ?? 'italent';

        switch ($type) {
            case 'italent':
                return self::createITalentTokenManager(
                    $config['app_key'],
                    $config['app_secret'],
                    $cache,
                    $httpClient
                );

            default:
                throw new \InvalidArgumentException("Unsupported token manager type: {$type}");
        }
    }
}
