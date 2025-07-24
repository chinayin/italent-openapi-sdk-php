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

use ITalentOpenSDK\Auth\TokenData;
use ITalentOpenSDK\Tests\TestCase;

class TokenDataTest extends TestCase
{
    public function testCreateTokenDataFromConstructor(): void
    {
        $tokenData = new TokenData(
            'test_access_token',
            'test_refresh_token',
            7200,
            'Bearer'
        );

        $this->assertEquals('test_access_token', $tokenData->getAccessToken());
        $this->assertEquals('test_refresh_token', $tokenData->getRefreshToken());
        $this->assertEquals(7200, $tokenData->getExpiresIn());
        $this->assertEquals('Bearer', $tokenData->getTokenType());
        $this->assertFalse($tokenData->isExpired());
    }

    public function testCreateTokenDataFromApiResponse(): void
    {
        $response = [
            'access_token' => 'api_access_token',
            'refresh_token' => 'api_refresh_token',
            'expires_in' => 3600,
            'token_type' => 'Bearer',
        ];

        $tokenData = TokenData::fromApiResponse($response);

        $this->assertEquals('api_access_token', $tokenData->getAccessToken());
        $this->assertEquals('api_refresh_token', $tokenData->getRefreshToken());
        $this->assertEquals(3600, $tokenData->getExpiresIn());
        $this->assertEquals('Bearer', $tokenData->getTokenType());
    }

    public function testCreateTokenDataFromArray(): void
    {
        $data = [
            'access_token' => 'cached_access_token',
            'refresh_token' => 'cached_refresh_token',
            'expires_at' => time() + 3600,
            'expires_in' => 7200,
            'token_type' => 'Bearer',
        ];

        $tokenData = TokenData::fromArray($data);

        $this->assertEquals('cached_access_token', $tokenData->getAccessToken());
        $this->assertEquals('cached_refresh_token', $tokenData->getRefreshToken());
        $this->assertEquals('Bearer', $tokenData->getTokenType());
    }

    public function testTokenExpiration(): void
    {
        // 创建已过期的令牌
        $expiredTokenData = new TokenData('expired_token', 'refresh_token', -100);
        $this->assertTrue($expiredTokenData->isExpired());

        // 创建有效的令牌
        $validTokenData = new TokenData('valid_token', 'refresh_token', 3600);
        $this->assertFalse($validTokenData->isExpired());
    }



    public function testGetRemainingTime(): void
    {
        $tokenData = new TokenData('test_token', 'refresh_token', 3600);
        $remaining = $tokenData->getRemainingTime();

        // 剩余时间应该接近3600秒（允许几秒的误差）
        $this->assertGreaterThan(3590, $remaining);
        $this->assertLessThanOrEqual(3600, $remaining);

        // 测试过期令牌
        $expiredTokenData = new TokenData('expired_token', 'refresh_token', -100);
        $this->assertEquals(0, $expiredTokenData->getRemainingTime());
    }

    public function testToArrayAndFromArray(): void
    {
        $originalTokenData = new TokenData(
            'test_access_token',
            'test_refresh_token',
            7200,
            'Bearer'
        );

        $array = $originalTokenData->toArray();
        $restoredTokenData = TokenData::fromArray($array);

        $this->assertEquals($originalTokenData->getAccessToken(), $restoredTokenData->getAccessToken());
        $this->assertEquals($originalTokenData->getRefreshToken(), $restoredTokenData->getRefreshToken());
        $this->assertEquals($originalTokenData->getTokenType(), $restoredTokenData->getTokenType());
    }

    public function testRefreshTokenExpirationLogic(): void
    {
        // 测试有刷新令牌的情况
        $tokenWithRefresh = new TokenData('access_token', 'refresh_token', 3600);
        $this->assertFalse($tokenWithRefresh->isExpired()); // 访问令牌未过期

        // 测试过期的访问令牌
        $expiredToken = new TokenData('expired_token', 'refresh_token', -100);
        $this->assertTrue($expiredToken->isExpired());
    }

    public function testFromApiResponseRequiresRefreshToken(): void
    {
        // 测试缺少 access_token 的情况
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('access_token and refresh_token are required in API response');

        TokenData::fromApiResponse([
            'expires_in' => 7200,
            'token_type' => 'Bearer',
        ]);
    }

    public function testFromApiResponseRequiresAccessToken(): void
    {
        // 测试缺少 refresh_token 的情况
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('access_token and refresh_token are required in API response');

        TokenData::fromApiResponse([
            'access_token' => 'test_token',
            'expires_in' => 7200,
            'token_type' => 'Bearer',
        ]);
    }

    public function testFromArrayRequiresTokens(): void
    {
        // 测试缺少必需字段的情况
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('access_token and refresh_token are required');

        TokenData::fromArray([
            'expires_in' => 7200,
            'token_type' => 'Bearer',
        ]);
    }
}
