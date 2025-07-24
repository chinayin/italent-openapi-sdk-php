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

use ITalentOpenSDK\Traits\CacheTrait;
use ITalentOpenSDK\Traits\HttpClientTrait;
use ITalentOpenSDK\Traits\SecretTrait;

/**
 * 抽象 Token 管理器
 * 使用模板方法模式定义 Token 管理的基本流程
 * 使用统一的 TokenData 对象进行缓存，简化存储逻辑
 */
abstract class AbstractTokenManager implements TokenStrategyInterface
{
    use CacheTrait;
    use HttpClientTrait;
    use SecretTrait;

    protected ?TokenData $tokenData = null;
    /** @var int 默认刷新阈值（秒） */
    protected int $refreshThreshold = 300;

    public function getAccessToken(bool $forceRefresh = false): string
    {
        if ($forceRefresh) {
            return $this->requestNewToken();
        }

        // 尝试从内存获取
        if ($this->tokenData && !$this->shouldRefresh()) {
            return $this->tokenData->getAccessToken();
        }

        // 尝试从缓存加载
        $this->loadTokenFromCache();

        if ($this->tokenData && !$this->shouldRefresh()) {
            return $this->tokenData->getAccessToken();
        }

        // 尝试刷新令牌（如果有令牌数据且未完全过期）
        if ($this->tokenData && !$this->tokenData->isExpired()) {
            try {
                return $this->refreshAccessToken();
            } catch (\Exception $e) {
                // 刷新失败，获取新令牌
                return $this->requestNewToken();
            }
        }

        // 获取新令牌
        return $this->requestNewToken();
    }

    public function getRefreshToken(): ?string
    {
        if (!$this->tokenData) {
            $this->loadTokenFromCache();
        }

        return $this->tokenData ? $this->tokenData->getRefreshToken() : null;
    }

    public function shouldRefresh(): bool
    {
        if (!$this->tokenData) {
            $this->loadTokenFromCache();
        }

        if (!$this->tokenData) {
            return true;
        }

        // 统一使用管理器的刷新阈值
        return time() >= ($this->tokenData->getExpiresAt() - $this->refreshThreshold);
    }

    public function refreshAccessToken(): string
    {
        if (!$this->tokenData) {
            return $this->requestNewToken();
        }

        $response = $this->performRefreshRequest($this->tokenData->getRefreshToken());
        $this->tokenData = TokenData::fromApiResponse($response);
        $this->saveTokenToCache();

        return $this->tokenData->getAccessToken();
    }

    public function clearTokens(): void
    {
        $this->cache->deleteItem($this->getTokenCacheKey());
        $this->tokenData = null;
    }

    /**
     * 请求新令牌
     */
    protected function requestNewToken(): string
    {
        $response = $this->performNewTokenRequest();
        $this->tokenData = TokenData::fromApiResponse($response);
        $this->saveTokenToCache();

        return $this->tokenData->getAccessToken();
    }

    /**
     * 从缓存加载令牌数据
     */
    protected function loadTokenFromCache(): void
    {
        $item = $this->cache->getItem($this->getTokenCacheKey());

        if ($item->isHit()) {
            $data = $item->get();
            if (is_array($data)) {
                $this->tokenData = TokenData::fromArray($data);
            }
        }
    }

    /**
     * 保存令牌数据到缓存
     */
    protected function saveTokenToCache(): void
    {
        if (!$this->tokenData) {
            return;
        }

        $item = $this->cache->getItem($this->getTokenCacheKey());
        $item->set($this->tokenData->toArray());

        // 设置缓存过期时间，提前刷新阈值时间过期以确保自动刷新
        $cacheExpire = max($this->refreshThreshold, $this->tokenData->getRemainingTime() - $this->refreshThreshold);
        $item->expiresAfter($cacheExpire);

        $this->cache->save($item);
    }

    /**
     * 获取当前令牌数据
     */
    public function getTokenData(): ?TokenData
    {
        if (!$this->tokenData) {
            $this->loadTokenFromCache();
        }

        return $this->tokenData;
    }

    /**
     * 获取令牌剩余时间
     */
    public function getTokenRemainingTime(): ?int
    {
        $tokenData = $this->getTokenData();
        return $tokenData ? $tokenData->getRemainingTime() : null;
    }

    /**
     * 检查令牌是否已过期
     */
    public function isTokenExpired(): bool
    {
        $tokenData = $this->getTokenData();
        return $tokenData ? $tokenData->isExpired() : true;
    }

    /**
     * 设置刷新阈值
     */
    public function setRefreshThreshold(int $seconds): void
    {
        $this->refreshThreshold = $seconds;
    }

    // 抽象方法，由具体实现类定义
    abstract protected function performNewTokenRequest(): array;

    abstract protected function performRefreshRequest(string $refreshToken): array;

    abstract protected function getTokenCacheKey(): string;
}
