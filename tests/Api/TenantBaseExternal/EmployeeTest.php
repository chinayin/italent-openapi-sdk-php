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

use ITalentOpenSDK\Api\TenantBaseExternal\Employee;
use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Tests\TestCase;

class EmployeeTest extends TestCase
{
    private Employee $api;

    protected function setUp(): void
    {
        parent::setUp();
        $this->api = $this->sdk->get('TenantBase.Employee');
    }

    public function testGetUserIDByEmail(): void
    {
        $email = 'xxx@example.com';
        $result = $this->api->getUserIDByEmail($email);
        $this->assertNotEmpty($result);
    }

    public function testGetUserIDsByJobNumbers(): void
    {
        $jobNumbers = ["5180", "5181"];
        $result = $this->api->getUserIDsByJobNumbers($jobNumbers);
        $this->assertNotEmpty($result);
    }

    public function testGetUserIDsByMobiles(): void
    {
        $mobiles = ["133xxxxxx"];
        $result = $this->api->getUserIDsByMobiles($mobiles);
        $this->assertNotEmpty($result);
    }

    public function testGetByTimeWindow(): void
    {
        $searchFilter = (new SearchFilter())
            ->setStartTime(date('Y-m-d', strtotime('-30 days')))
            ->setStopTime(date('Y-m-d'))
            ->setCapacity(2);

        $result = $this->api->getByTimeWindow($searchFilter);
        $this->assertTrue($result->hasMore());
        $this->assertNotEmpty($result->items());
    }

    public function testGetEmployeeOfOrganization(): void
    {
        $orgOId = OrganizationTest::getTenantOrgId();
        $searchFilter = (new SearchFilter())
            ->addExtraParam('orgOId', $orgOId)
            ->setCapacity(2);

        $result = $this->api->getEmployeeOfOrganization($searchFilter);
        $this->assertTrue($result->hasMore());
        $this->assertNotEmpty($result->items());
    }

    public function testGetBasicInfoByIds(): void
    {
        $oIds = [632491145];
        $result = $this->api->getBasicInfoByIds($oIds);
        $this->assertTrue($result->hasMore());
        $this->assertNotEmpty($result->items());
    }

    public function testGetJuniorById(): void
    {
        $oId = 630144054;
        $columns = ['userID', 'name', 'email'];
        $result = $this->api->getJuniorById($oId, (new SearchFilter())->setColumns($columns));
        $this->assertTrue($result->hasMore());
        $this->assertNotEmpty($result->items());
    }

    public function testGetServiceInfoByIds(): void
    {
        $oIds = [632491145];
        $searchFilter = (new SearchFilter())
            ->setOIds($oIds)
            ->addExtraParam('option', 2);

        $result = $this->api->getServiceInfoByIds($searchFilter);
        $this->assertNotEmpty($result->items());
    }

}
