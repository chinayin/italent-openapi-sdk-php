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

use Psr\Http\Message\StreamInterface;

interface HttpClientInterface
{
    public function get(string $uri, array $query = []): array;

    public function getStream(string $uri, array $query = []): StreamInterface;

    public function postJson(string $uri, array $json = [], array $query = []): array;

    public function postJsonStream(string $uri, array $json = [], array $query = []): StreamInterface;

    public function postFile(string $uri, string $path, array $query = []): array;

    public function delete(string $uri, array $query = []): array;
}
