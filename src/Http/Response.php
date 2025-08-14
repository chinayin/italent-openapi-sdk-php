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

use ITalentOpenSDK\Exception\ITalentException;
use Psr\Http\Message\StreamInterface;

class Response extends \GuzzleHttp\Psr7\Response
{
    public function getBody(): StreamInterface
    {
        $stream = parent::getBody();

        // 移除 ASCII 控制字符（0x00-0x1F, 0x7F），保留标准的 \n \r \t
        $cleaned = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', (string)$stream);

        $data = json_decode($cleaned, true);
        if (JSON_ERROR_NONE === json_last_error()) {
            // 检查北森 API 的错误响应格式
            // 统一兼容 'code' 和 'Code'
            $code = $data['code'] ?? $data['Code'] ?? null;
            if ($code !== null && (string)$code !== '200') {
                throw new ITalentException(
                    $data['message'] ?? $data['Message'] ?? 'API Error',
                    (int)$code,
                    null,
                    $data
                );
            }
        }

        // 包回 StreamInterface
        if (class_exists(\GuzzleHttp\Psr7\Utils::class)) {
            // Guzzle 7+
            return \GuzzleHttp\Psr7\Utils::streamFor($cleaned);
        } else {
            // Guzzle 6.x
            return \GuzzleHttp\Psr7\stream_for($cleaned);
        }
    }

    public function toArray(): array
    {
        return json_decode((string)$this->getBody(), true) ?? [];
    }
}
