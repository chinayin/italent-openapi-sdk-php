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

use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

class HttpClient implements HttpClientInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get(string $uri, array $query = []): array
    {
        return $this->client->get($uri, compact('query'))->toArray();
    }

    public function getStream(string $uri, array $query = []): StreamInterface
    {
        return $this->client->get($uri, compact('query'))->getBody();
    }

    public function postJson(string $uri, array $json = [], array $query = []): array
    {
        return $this->client->post($uri, compact('json', 'query'))->toArray();
    }

    public function postJsonStream(string $uri, array $json = [], array $query = []): StreamInterface
    {
        return $this->client->post($uri, compact('json', 'query'))->getBody();
    }

    public function postFile(string $uri, string $path, array $query = []): array
    {
        return $this->client->post(
            $uri,
            array_merge([
                'multipart' => [
                    [
                        'name' => 'media',
                        'contents' => fopen($path, 'r'),
                    ],
                ],
            ], compact('query'))
        )->toArray();
    }

    public function delete(string $uri, array $query = []): array
    {
        return $this->client->delete($uri, compact('query'))->toArray();
    }
}
