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

namespace ITalentOpenSDK\Traits;

trait SecretTrait
{
    protected string $appKey;
    protected string $appSecret;

    public function setAppKey(string $appKey): void
    {
        $this->appKey = $appKey;
    }

    public function getAppKey(): string
    {
        return $this->appKey;
    }

    public function setAppSecret(string $appSecret): void
    {
        $this->appSecret = $appSecret;
    }

    public function getAppSecret(): string
    {
        return $this->appSecret;
    }
}
