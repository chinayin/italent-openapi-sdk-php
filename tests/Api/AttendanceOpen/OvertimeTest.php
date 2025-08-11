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

use ITalentOpenSDK\Api\AttendanceOpen\Overtime;
use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Tests\TestCase;

class OvertimeTest extends TestCase
{
    private Overtime $api;

    protected function setUp(): void
    {
        parent::setUp();
        $this->api = $this->sdk->get('AttendanceOpen.Overtime');
    }

    public function testGetScheduledOverTimeRangeList(): void
    {
        $day1 = '2025-07-01';
        $day2 = '2025-08-05';
        $userIds = [630144054];

        $result = $this->api->getScheduledOverTimeRangeList($userIds, $day1, $day2);
        $this->assertNotEmpty($result->items());
    }

    public function testGetOverTimeListByDate(): void
    {
        $day = '2025-07-01';

        $result = $this->api->getOverTimeListByDate($day);
        $this->assertNotEmpty($result->items());
    }

    public function testGetApprovalCompletedOverTimeListByDateTime(): void
    {
        $filter = new SearchFilter();
        $filter->addExtraParam('startDate', '2025-07-01')
            ->addExtraParam('endDate', '2025-07-01');
        $filter->addExtraParam('userIds', [630144054]);

        $result = $this->api->getApprovalCompletedOverTimeListByDateTime($filter);
        $this->assertNotEmpty($result->items());
    }

}
