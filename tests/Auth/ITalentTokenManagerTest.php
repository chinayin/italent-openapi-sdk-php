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

use ITalentOpenSDK\Auth\ITalentTokenManager;
use ITalentOpenSDK\Exception\ITalentException;

class ITalentTokenManagerTest extends AbstractTokenManagerTestCase
{
    private ITalentTokenManager $tokenManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tokenManager = new ITalentTokenManager();
        $this->tokenManager->setAppKey('test_app_key');
        $this->tokenManager->setAppSecret('test_app_secret');
        $this->tokenManager->setHttpClient($this->mockHttpClient);
        $this->tokenManager->setCache($this->mockCache);
    }

    public function testGetAccessTokenWithNewToken(): void
    {
        $this->mockCacheMiss();

        $tokenResponse = $this->createTokenResponse('new_access_token');

        $this->mockHttpClient->shouldReceive('postJson')
            ->with('token', [
                'grant_type' => 'client_credentials',
                'app_key' => 'test_app_key',
                'app_secret' => 'test_app_secret',
            ])
            ->andReturn($tokenResponse);

        $this->mockCacheSave();

        $accessToken = $this->tokenManager->getAccessToken();

        $this->assertEquals('new_access_token', $accessToken);
    }

    public function testGetAccessTokenWithCachedValidToken(): void
    {
        $cachedData = $this->createCachedTokenData('cached_access_token', time() + 3600);
        $this->mockCacheHit($cachedData);

        $accessToken = $this->tokenManager->getAccessToken();

        $this->assertEquals('cached_access_token', $accessToken);
    }

    public function testGetAccessTokenWithExpiredCachedToken(): void
    {
        // 缓存中有过期的令牌
        $expiredCachedData = $this->createCachedTokenData('expired_token', time() - 100);
        $this->mockCacheHit($expiredCachedData);

        // 应该请求新令牌
        $newTokenResponse = $this->createTokenResponse('new_fresh_token');
        $this->mockHttpClient->shouldReceive('postJson')
            ->with('token', [
                'grant_type' => 'client_credentials',
                'app_key' => 'test_app_key',
                'app_secret' => 'test_app_secret',
            ])
            ->andReturn($newTokenResponse);

        $this->mockCacheSave();

        $accessToken = $this->tokenManager->getAccessToken();

        $this->assertEquals('new_fresh_token', $accessToken);
    }

    public function testRefreshAccessToken(): void
    {
        // 首先加载令牌数据到内存
        $soonExpireData = $this->createCachedTokenData('soon_expire_token', time() + 200);
        $this->mockCacheHit($soonExpireData);

        // 先调用一次getAccessToken来加载令牌数据
        $this->tokenManager->getTokenData();

        // Mock 刷新请求
        $refreshResponse = $this->createTokenResponse('refreshed_access_token', 'new_refresh_token');
        $this->mockHttpClient->shouldReceive('postJson')
            ->with('token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => 'cached_refresh_token',
            ])
            ->once()
            ->andReturn($refreshResponse);

        $this->mockCacheSave();

        $accessToken = $this->tokenManager->refreshAccessToken();

        $this->assertEquals('refreshed_access_token', $accessToken);
    }

    public function testShouldRefreshWhenTokenExpiringSoon(): void
    {
        $soonExpireData = $this->createCachedTokenData('soon_expire_token', time() + 200);
        $this->mockCacheHit($soonExpireData);

        $this->assertTrue($this->tokenManager->shouldRefresh());
    }

    public function testShouldNotRefreshWhenTokenValid(): void
    {
        $validData = $this->createCachedTokenData('valid_token', time() + 3600);
        $this->mockCacheHit($validData);

        $this->assertFalse($this->tokenManager->shouldRefresh());
    }

    public function testClearTokens(): void
    {
        $this->mockCache->shouldReceive('deleteItem')->once()->andReturn(true);
        $this->mockCacheMiss(); // 清除后缓存应该为空

        $this->tokenManager->clearTokens();

        // 验证令牌数据被清除
        $this->assertNull($this->tokenManager->getTokenData());
    }

    public function testGetTokenRemainingTime(): void
    {
        $validData = $this->createCachedTokenData('valid_token', time() + 1800); // 30分钟后过期
        $this->mockCacheHit($validData);

        $remaining = $this->tokenManager->getTokenRemainingTime();

        $this->assertGreaterThan(1700, $remaining);
        $this->assertLessThanOrEqual(1800, $remaining);
    }

    public function testIsTokenExpired(): void
    {
        // 测试过期令牌
        $expiredData = $this->createCachedTokenData('expired_token', time() - 100);
        $this->mockCacheHit($expiredData);

        $this->assertTrue($this->tokenManager->isTokenExpired());

        // 重新设置mock以测试有效令牌
        $this->setUp();
        $this->tokenManager = new ITalentTokenManager();
        $this->tokenManager->setAppKey('test_app_key');
        $this->tokenManager->setAppSecret('test_app_secret');
        $this->tokenManager->setHttpClient($this->mockHttpClient);
        $this->tokenManager->setCache($this->mockCache);

        // 测试有效令牌
        $validData = $this->createCachedTokenData('valid_token', time() + 3600);
        $this->mockCacheHit($validData);

        $this->assertFalse($this->tokenManager->isTokenExpired());
    }

    public function testSetRefreshThreshold(): void
    {
        $this->tokenManager->setRefreshThreshold(600); // 10分钟

        // 创建一个8分钟后过期的令牌
        $soonExpireData = $this->createCachedTokenData('soon_expire_token', time() + 480);
        $this->mockCacheHit($soonExpireData);

        // 应该需要刷新（因为8分钟 < 10分钟阈值）
        $this->assertTrue($this->tokenManager->shouldRefresh());
    }

    public function testTokenManagerThrowsExceptionOnFailedRequest(): void
    {
        $this->mockCacheMiss();

        $errorResponse = [
            'error' => 'invalid_client',
            'message' => 'Invalid client credentials',
        ];

        $this->mockHttpClient->shouldReceive('postJson')->andReturn($errorResponse);

        $this->expectException(ITalentException::class);
        $this->expectExceptionMessage('Failed to get access token: Invalid client credentials');

        $this->tokenManager->getAccessToken();
    }

    public function testTokenManagerThrowsExceptionOnFailedRefresh(): void
    {
        $validData = $this->createCachedTokenData();
        $this->mockCacheHit($validData);

        // 先加载令牌数据
        $this->tokenManager->getTokenData();

        $errorResponse = [
            'error' => 'invalid_grant',
            'message' => 'Invalid refresh token',
        ];

        $this->mockHttpClient->shouldReceive('postJson')
            ->with('token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => 'cached_refresh_token',
            ])
            ->once()
            ->andReturn($errorResponse);

        $this->expectException(ITalentException::class);
        $this->expectExceptionMessage('Failed to refresh access token: Invalid refresh token');

        $this->tokenManager->refreshAccessToken();
    }
}
