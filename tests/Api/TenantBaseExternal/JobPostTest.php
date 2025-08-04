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

namespace ITalentOpenSDK\Tests\Api\TenantBaseExternal;

use ITalentOpenSDK\Api\TenantBaseExternal\JobPost;
use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Tests\TestCase;

class JobPostTest extends TestCase
{
    private JobPost $api;

    protected function setUp(): void
    {
        parent::setUp();
        $this->api = $this->sdk->get('TenantBase.JobPost');
    }

    public function testGetByTimeWindow(): void
    {
        $searchFilter = (new SearchFilter())
            ->setStartTime(date('Y-m-d', strtotime('-30 days')))
            ->setStopTime(date('Y-m-d'))
            ->setCapacity(2);

        $result = $this->api->getByTimeWindow($searchFilter);
        $this->assertNotEmpty($result->items());
    }

    public function testGetByOIds(): void
    {
        $oIds = ["520354", "520353"];
        $result = $this->api->getByOIds($oIds);
        $this->assertNotEmpty($result->items());
    }

}
