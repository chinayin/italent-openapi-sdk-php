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

namespace ITalentOpenSDK\Tests\Api\AttendanceOpen;

use ITalentOpenSDK\Api\AttendanceOpen\VacationRemain;
use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Tests\TestCase;

class VacationRemainTest extends TestCase
{
    private VacationRemain $api;

    protected function setUp(): void
    {
        parent::setUp();
        $this->api = $this->sdk->get('AttendanceOpen.VacationRemain');
    }

    public function testGetListByUserId(): void
    {
        $userId = 630144054;

        $result = $this->api->getListByUserId($userId);
        $this->assertNotEmpty($result->items());
    }

    public function testGetVacationRemainList(): void
    {
        $filter = new SearchFilter();
        $filter->addExtraParam('year', '2025')
            ->addExtraParam('vacationItemCode', 'AdjustLeave');
        $result = $this->api->getVacationRemainList($filter);
        $this->assertNotEmpty($result->items());
    }
}
