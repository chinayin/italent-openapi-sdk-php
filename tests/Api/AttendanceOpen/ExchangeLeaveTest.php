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

use ITalentOpenSDK\Api\AttendanceOpen\ExchangeLeave;
use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Tests\TestCase;

class ExchangeLeaveTest extends TestCase
{
    private ExchangeLeave $api;

    protected function setUp(): void
    {
        parent::setUp();
        $this->api = $this->sdk->get('AttendanceOpen.ExchangeLeave');
    }

    public function testDetails(): void
    {
        $filter = new SearchFilter();
        $filter->addExtraParam('IdentityType', 1)
            ->addExtraParam('StaffIds', [630144054])
            ->addExtraParam('OvertimeDateStart', '2025-07-01 00:00:00')
            ->addExtraParam('OvertimeDateEnd', '2025-07-30 00:00:00')
            ->addExtraParam('ValidityDateStart', '2025-07-01 00:00:00')
            ->addExtraParam('ValidityDateEnd', '2025-12-31 00:00:00');

        $result = $this->api->details($filter);
        $this->assertNotEmpty($result->items());
    }

    public function testQuerySettlementAdjustRemains(): void
    {
        $userIds = [630144054];

        $result = $this->api->querySettlementAdjustRemains($userIds);
        $this->assertNotEmpty($result->items());
    }

}
