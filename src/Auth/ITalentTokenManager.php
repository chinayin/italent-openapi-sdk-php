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

use ITalentOpenSDK\Exception\ITalentException;

/**
 * 北森 Token 管理器
 * 实现北森特定的 Token 获取和刷新逻辑
 */
class ITalentTokenManager extends AbstractTokenManager
{
    protected function performNewTokenRequest(): array
    {
        $response = $this->httpClient->postJson('token', [
            'grant_type' => 'client_credentials',
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
        ]);

        if (!isset($response['access_token'])) {
            throw new ITalentException(
                'Failed to get access token: ' . ($response['message'] ?? 'Unknown error'),
                0,
                null,
                $response
            );
        }

        return $response;
    }

    protected function performRefreshRequest(string $refreshToken): array
    {
        $response = $this->httpClient->postJson('token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]);

        if (!isset($response['access_token'])) {
            throw new ITalentException(
                'Failed to refresh access token: ' . ($response['message'] ?? 'Unknown error'),
                0,
                null,
                $response
            );
        }

        return $response;
    }

    protected function getTokenCacheKey(): string
    {
        $unique = md5("{$this->appKey}__{$this->appSecret}");
        return md5('italent.openapi.token.' . $unique);
    }
}
