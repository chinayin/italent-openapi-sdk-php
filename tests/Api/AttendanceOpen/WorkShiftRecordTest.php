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

use ITalentOpenSDK\Api\AttendanceOpen\WorkShiftRecord;
use ITalentOpenSDK\Tests\TestCase;

class WorkShiftRecordTest extends TestCase
{
    private WorkShiftRecord $api;

    protected function setUp(): void
    {
        parent::setUp();
        $this->api = $this->sdk->get('AttendanceOpen.WorkShiftRecord');
    }

    public function testGetWorkShiftRecordListByMonth(): void
    {
        $month = '2025-08-01';

        $result = $this->api->getWorkShiftRecordListByMonth($month);
        var_dump($result->scrollId());
        file_put_contents("/Users/tian/Sites/github/chinayin/italent-openapi-sdk-php/runtime/italent_sdk_test/log/aa.json", json_encode($result->items(), JSON_UNESCAPED_UNICODE));
        $this->assertNotEmpty($result->items());
    }

}
