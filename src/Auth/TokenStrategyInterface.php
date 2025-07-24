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

/**
 * Token 管理策略接口
 */
interface TokenStrategyInterface
{
    /**
     * 获取访问令牌
     */
    public function getAccessToken(bool $forceRefresh = false): string;

    /**
     * 获取刷新令牌
     */
    public function getRefreshToken(): ?string;

    /**
     * 刷新访问令牌
     */
    public function refreshAccessToken(): string;

    /**
     * 检查是否需要刷新令牌
     */
    public function shouldRefresh(): bool;

    /**
     * 清除所有令牌
     */
    public function clearTokens(): void;

    /**
     * 设置应用密钥
     */
    public function setAppKey(string $appKey): void;

    /**
     * 设置应用秘钥
     */
    public function setAppSecret(string $appSecret): void;

    /**
     * 设置缓存
     */
    public function setCache(\Psr\Cache\CacheItemPoolInterface $cache): void;

    /**
     * 设置 HTTP 客户端
     */
    public function setHttpClient(\ITalentOpenSDK\Http\HttpClientInterface $httpClient): void;
}
