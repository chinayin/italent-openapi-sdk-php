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

namespace ITalentOpenSDK\Tests\Api\Compensation;

use ITalentOpenSDK\Api\Compensation\PresetSalarySubset;
use ITalentOpenSDK\Constants;
use ITalentOpenSDK\Model\SalarySubsetData;
use ITalentOpenSDK\Tests\TestCase;

class PresetSalarySubsetTest extends TestCase
{
    private PresetSalarySubset $api;

    protected function setUp(): void
    {
        parent::setUp();
        $this->api = $this->sdk->get('Compensation.PresetSalarySubset');
    }

    public function testAddOrEdit(): void
    {
        $data = new SalarySubsetData();
        //        $data->setStaffId("630143787")
        //            ->setCustomFields([
        //                'extsalaryMonth_614181_2006513888' => '2025-08',
        //                'extcurrencySymbol_614181_852666027' => 'CNY',
        //                'exttotalSalary_614181_1689881326' => 25906.51,
        //                'extbasicSalary_614181_1204972321' => 7000,
        //                'extbusinessSalary_614181_397273561' => 15426.51,
        //                'extmanagerSalary_614181_1299076106' => 0,
        //                'extapplyBonus_614181_443680259' => 0,
        //                'extrankingReward_614181_954614424' => 1000,
        //                'extonlinePushSalary_614181_2084683026' => 0,
        //                'extpromotionReward_614181_700194380' => 300,
        //                'extpayrentReward_614181_441079462' => 180,
        //            ]);

        $data->setStaffId("630143980")
            ->setCustomFields([
                'extsalaryMonth_614181_2006513888' => '2025-08',
                'extcurrencySymbol_614181_852666027' => 'CNY',
                'exttotalSalary_614181_1689881326' => 21897.75,
                'extbasicSalary_614181_1204972321' => 11625,
                'extbusinessSalary_614181_397273561' => 5262.75,
                'extmanagerSalary_614181_1299076106' => 5000,
                'extapplyBonus_614181_443680259' => 0,
                'extrankingReward_614181_954614424' => 0,
                'extonlinePushSalary_614181_2084683026' => 0,
                'extpromotionReward_614181_700194380' => 0,
                'extpayrentReward_614181_441079462' => 10,
            ]);

        $result = $this->api->addOrEdit(Constants::SALARY_SUBSET_PRESET_1, $data);
        $this->assertNull($result);
    }

}
