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
        $data = json_decode((string)$stream, true);
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

        return $stream;
    }

    public function toArray(): array
    {
        return json_decode((string)$this->getBody(), true) ?? [];
    }
}
