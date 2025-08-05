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

use ITalentOpenSDK\Api\AttendanceOpen\Vacation;
use ITalentOpenSDK\Tests\TestCase;

class VacationTest extends TestCase
{
    private Vacation $api;

    protected function setUp(): void
    {
        parent::setUp();
        $this->api = $this->sdk->get('AttendanceOpen.Vacation');
    }

    public function testGetVacationRemainList(): void
    {
        $day = '2025-07-01';

        $result = $this->api->getListByDate($day);
        $this->assertNotEmpty($result->items());
    }

    public function testGetVacationMapping(): void
    {
        $day1 = '2025-07-01';
        $day2 = '2025-08-05';
        $userIds = [630144054];

        $result = $this->api->getVacationMapping($userIds, $day1, $day2);
        $this->assertNotEmpty($result->items());
    }

    public function testGetVacationMappingIncludeApproving(): void
    {
        $day1 = '2025-08-01';
        $day2 = '2025-08-05';
        $userIds = [630144054];

        $result = $this->api->getVacationMappingIncludeApproving($userIds, $day1, $day2);
        $this->assertNotEmpty($result->items());
    }

}
