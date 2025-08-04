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

use ITalentOpenSDK\Api\AttendanceOpen\WorkShift;
use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Tests\TestCase;

class WorkShiftTest extends TestCase
{
    private WorkShift $api;

    protected function setUp(): void
    {
        parent::setUp();
        $this->api = $this->sdk->get('AttendanceOpen.WorkShift');
    }

    public function testBatchGetWorkShiftByStaffIdDate(): void
    {
        $result = $this->api->batchGetWorkShiftByStaffIdDate([629813113, 630143431], '2025-07-01', '2025-07-31');
        $this->assertNotEmpty($result->items());
    }

    public function testGetWorkShiftByUserId(): void
    {
        $filter = new SearchFilter();
        $filter->addExtraParam('UserDates', [
            ['UserId' => 629813113, 'CardDateTime' => '2025-07-01',],
        ]);
        $result = $this->api->getWorkShiftByUserId($filter);
        $this->assertNotEmpty($result);
    }

    public function testGet(): void
    {
        $filter = new SearchFilter();
        $filter->addExtraParam('StaffCardDates', [
            'CardNumber' => '4735',
            'CardDateTime' => '2025-08-01',
        ]);
        $result = $this->api->get($filter);
        $this->assertNotEmpty($result);
    }

    public function testGetWorkShiftInfo(): void
    {
        $result = $this->api->getWorkShiftInfo();
        $this->assertNotEmpty($result->items());
    }

}
