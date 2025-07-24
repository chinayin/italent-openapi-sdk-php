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

namespace ITalentOpenSDK\Tests\Api;

use ITalentOpenSDK\Api\OpenApi;
use ITalentOpenSDK\Tests\TestCase;

/**
 * OpenAPI 真实调用测试
 * 测试通过 ITalentSDK 调用真实的 API 接口
 */
class OpenApiTest extends TestCase
{
    private OpenApi $openapi;

    protected function setUp(): void
    {
        parent::setUp();

        // 获取 openapi 服务
        $this->openapi = $this->sdk->get('openapi');
    }

    public function testCanGetOpenapiService(): void
    {
        $this->assertInstanceOf(OpenApi::class, $this->openapi);
    }

    public function testGetOrganizationByTimeWindow(): void
    {
        $uri = 'TenantBaseExternal/api/v5/Organization/GetByTimeWindow';

        // 设置查询参数 - 获取最近30天的组织变更
        $query = [
            'startTime' => date('Y-m-d', strtotime('-30 days')),
            'stopTime' => date('Y-m-d'),
            'timeWindowQueryType' => 1,
            'scrollId' => "",
        ];
        $result = $this->openapi->post($uri, $query);

        $this->assertNotEmpty($result);
    }

}
