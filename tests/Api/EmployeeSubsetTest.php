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

use ITalentOpenSDK\Api\EmployeeSubset;
use ITalentOpenSDK\Constants;
use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Tests\TestCase;

class EmployeeSubsetTest extends TestCase
{
    private EmployeeSubset $api;

    protected function setUp(): void
    {
        parent::setUp();
        $this->api = $this->sdk->get('EmployeeSubset');
    }

    public function testGetByTimeWindow(): void
    {
        $searchFilter = (new SearchFilter())
            ->setStartTime(date('Y-m-d', strtotime('-30 days')))
            ->setStopTime(date('Y-m-d'))
            ->setCapacity(2);

        $result = $this->api->getByTimeWindow(Constants::SUBSET_JOB_HISTORY, $searchFilter);
        $this->assertNotEmpty($result->items());
    }

    public function testGetSubsetByIds(): void
    {
        $oIds = ['5ffeb0c6-9f43-471a-88f0-1e902471b0d6', '04ea3a41-4e0a-41b1-a709-d4650158b024'];
        $result = $this->api->getSubsetByIds(Constants::SUBSET_JOB_HISTORY, $oIds);
        $this->assertNotEmpty($result->items());
    }

    public function testGetSubsetByUserId(): void
    {
        $oId = 630143176;
        $result = $this->api->getSubsetByUserId(Constants::SUBSET_JOB_HISTORY, $oId);
        $this->assertNotEmpty($result->items());
    }

}
