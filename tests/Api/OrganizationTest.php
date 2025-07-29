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

use ITalentOpenSDK\Api\TenantBaseExternal\Organization;
use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Tests\TestCase;

class OrganizationTest extends TestCase
{
    private Organization $api;

    protected function setUp(): void
    {
        parent::setUp();
        $this->api = $this->sdk->get('TenantBase.Organization');
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

    public function testGetByIds(): void
    {
        $oIds = [900614181, 2190964];
        $result = $this->api->getByIds($oIds);
        $this->assertNotEmpty($result->items());
    }

    public function testGetOrganizationInfoByCodes(): void
    {
        $codes = ['RootOrg', 'U0154'];
        $result = $this->api->getOrganizationInfoByCodes($codes);
        $this->assertNotEmpty($result->items());
    }

    public static function getTenantOrgId(): int
    {
        $tenantId = getenv('TENANT_ID');
        return intval("900$tenantId");
    }

    public function testGetSubOrganizations(): void
    {
        $oId = $this->getTenantOrgId();
        $result = $this->api->getSubOrganizations($oId);
        $this->assertNotEmpty($result->items());
    }

    public function testGetSubOrganizationsWithDeleted(): void
    {
        $oId = $this->getTenantOrgId();
        $searchFilter = new SearchFilter();
        $searchFilter->setIsWithDeleted(true);
        $searchFilter->setExtraParam('isWithSelf', true);
        $searchFilter->setExtraParam('isWithDisable', true);
        $result = $this->api->getSubOrganizations($oId, $searchFilter);
        $this->assertNotEmpty($result->items());
    }

}
