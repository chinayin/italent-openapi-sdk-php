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
 * Token 数据封装类 - 值对象模式
 */
class TokenData
{
    private string $accessToken;
    private string $refreshToken;
    private int $expiresAt;
    private int $expiresIn;
    private string $tokenType;

    public function __construct(
        string $accessToken,
        string $refreshToken,
        /** @var int 默认访问令牌有效期（秒） */
        int    $expiresIn = 7200,
        string $tokenType = 'Bearer'
    )
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->expiresIn = $expiresIn;
        $this->expiresAt = time() + $expiresIn;
        $this->tokenType = $tokenType;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getExpiresAt(): int
    {
        return $this->expiresAt;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function isExpired(): bool
    {
        return time() >= $this->expiresAt;
    }

    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'expires_at' => $this->expiresAt,
            'expires_in' => $this->expiresIn,
            'token_type' => $this->tokenType,
        ];
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['access_token']) || !isset($data['refresh_token'])) {
            throw new \InvalidArgumentException('access_token and refresh_token are required');
        }

        $instance = new self(
            $data['access_token'],
            $data['refresh_token'],
            $data['expires_in'],
            $data['token_type']
        );

        if (isset($data['expires_at'])) {
            $instance->expiresAt = $data['expires_at'];
        }

        return $instance;
    }

    /**
     * 从 API 响应创建 TokenData 实例
     */
    public static function fromApiResponse(array $response): self
    {
        if (!isset($response['access_token']) || !isset($response['refresh_token'])) {
            throw new \InvalidArgumentException('access_token and refresh_token are required in API response');
        }

        return new self(
            $response['access_token'],
            $response['refresh_token'],
            $response['expires_in'],
            $response['token_type']
        );
    }

    /**
     * 获取剩余有效时间（秒）
     */
    public function getRemainingTime(): int
    {
        $remaining = $this->expiresAt - time();
        return max(0, $remaining);
    }
}
