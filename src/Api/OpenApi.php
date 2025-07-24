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

namespace ITalentOpenSDK\Api;

use ITalentOpenSDK\Traits\HttpClientTrait;

/**
 * 北森 OpenAPI 通用 API 调用基类
 * 支持调用不同模块的API接口
 */
class OpenApi
{
    use HttpClientTrait;

    /**
     * 发起 GET 请求
     *
     * @param string $uri API路径，如：TenantBaseExternal/api/v5/OrganizationType/GetOrganizationTypeByName
     * @param array $query 查询参数
     * @return array
     */
    public function get(string $uri, array $query = []): array
    {
        return $this->httpClient->get($uri, $query);
    }

    /**
     * 发起 POST 请求
     *
     * @param string $uri API路径
     * @param array $data 请求数据
     * @param array $query 查询参数
     * @return array
     */
    public function post(string $uri, array $data = [], array $query = []): array
    {
        return $this->httpClient->postJson($uri, $data, $query);
    }

    /**
     * 发起 DELETE 请求
     *
     * @param string $uri API路径
     * @param array $query 查询参数
     * @return array
     */
    public function delete(string $uri, array $query = []): array
    {
        return $this->httpClient->delete($uri, $query);
    }

}
