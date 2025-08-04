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

use ITalentOpenSDK\Api\TenantBaseExternal\EmployeeSubset;
use ITalentOpenSDK\Constants;
use ITalentOpenSDK\Model\EmployeeSubsetData;
use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Tests\TestCase;

class EmployeeSubsetTest extends TestCase
{
    private EmployeeSubset $api;

    protected function setUp(): void
    {
        parent::setUp();
        $this->api = $this->sdk->get('TenantBase.EmployeeSubset');
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
        $oId = 629813113;
        $result = $this->api->getSubsetByUserId(Constants::SUBSET_PRESET_2, $oId);
        $this->assertNotEmpty($result->items());
    }

    public function testCreate(): void
    {
        $data = new EmployeeSubsetData();
        $data->setUserId(629813113)
            ->setFields(['ModifiedTime' => date('Y-m-d\TH:i:s')])
            ->setCustomProperties([
                'extAccountType_xxx_789237906' => 'CRM',
            ]);
        $result = $this->api->create(Constants::SUBSET_PRESET_2, $data);
        $this->assertNotNull($result);
    }

    public function testUpdate(): void
    {
        $data = new EmployeeSubsetData();
        $data->setUserId(629813113)
            ->setObjectId('b1949efa-ce84-43dd-a503-f3055a36aadd')
            ->setFields(['ModifiedTime' => date('Y-m-d\TH:i:s')])
            ->setCustomProperties([
                'extAccountType_xxx_789237906' => 'CRM',
            ]);
        $result = $this->api->update(Constants::SUBSET_PRESET_2, $data);
        $this->assertTrue($result);
    }

}
