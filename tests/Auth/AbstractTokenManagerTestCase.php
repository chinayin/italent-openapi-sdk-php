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

namespace ITalentOpenSDK\Tests\Auth;

use ITalentOpenSDK\Http\HttpClientInterface;
use ITalentOpenSDK\Tests\TestCase;
use Mockery;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Token 管理器测试基类
 * 提供通用的 Mock 对象和辅助方法
 */
abstract class AbstractTokenManagerTestCase extends TestCase
{
    protected $mockHttpClient;
    protected $mockCache;
    protected $mockCacheItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHttpClient = Mockery::mock(HttpClientInterface::class);
        $this->mockCache = Mockery::mock(CacheItemPoolInterface::class);
        $this->mockCacheItem = Mockery::mock(CacheItemInterface::class);
    }

    /**
     * 创建缓存未命中的 Mock
     */
    protected function mockCacheMiss(): void
    {
        $this->mockCacheItem->shouldReceive('isHit')->andReturn(false);
        $this->mockCache->shouldReceive('getItem')->andReturn($this->mockCacheItem);
    }

    /**
     * 创建缓存命中的 Mock
     */
    protected function mockCacheHit(array $data): void
    {
        $this->mockCacheItem->shouldReceive('isHit')->andReturn(true);
        $this->mockCacheItem->shouldReceive('get')->andReturn($data);
        $this->mockCache->shouldReceive('getItem')->andReturn($this->mockCacheItem);
    }

    /**
     * Mock 缓存保存操作
     */
    protected function mockCacheSave(): void
    {
        $this->mockCacheItem->shouldReceive('set')->andReturnSelf();
        $this->mockCacheItem->shouldReceive('expiresAfter')->andReturnSelf();
        $this->mockCache->shouldReceive('save')->andReturn(true);
    }

    /**
     * 创建标准的 Token 响应数据
     */
    protected function createTokenResponse(
        string $accessToken = 'test_access_token',
        string $refreshToken = 'test_refresh_token',
        int $expiresIn = 7200
    ): array {
        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_in' => $expiresIn,
            'token_type' => 'Bearer',
        ];
    }

    /**
     * 创建缓存的 Token 数据
     */
    protected function createCachedTokenData(
        string $accessToken = 'cached_access_token',
        int $expiresAt = null
    ): array {
        $expiresAt ??= (time() + 3600);

        return [
            'access_token' => $accessToken,
            'refresh_token' => 'cached_refresh_token',
            'expires_at' => $expiresAt,
            'expires_in' => 7200,
            'token_type' => 'Bearer',
        ];
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
