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
                'extRevision_614181_1492416513' => $this->generateRevisionId(),
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
        $this->assertTrue($result->isSuccess());
    }

    public function testGenerateRevisionId(): void
    {
        $id = $this->generateRevisionId();
        $this->assertNotEmpty($id);
        $id = $this->generateRevisionId($id);
        $this->assertNotEmpty($id);
    }

    /**
     * 生成版本号：
     * - 若传入 $lastRevision：仅对末尾批次数字 +1，并按「数字判断」规则补零。
     * - 否则新生成：date('ymdH') + 2位大写字母(去 I,O,S,Z) + '01'。
     */
    private function generateRevisionId(?string $lastRevision = null): string
    {
        // 传入了上一个版本号就只对尾部批次数字 +1
        if ($lastRevision !== null
            && preg_match('/^(\d{8})([A-Z]{2})(\d+)$/', $lastRevision, $m)) {
            // 标准格式：8位数字 + 2位字母 + 至少2位数字
            $next = (int)$m[3] + 1;
            // 两位数优化：当下一批次 <10 时补一个前导零；否则直接输出数字（>99 将自然扩展为3位或更多）
            $batch = ($next < 10) ? ('0' . $next) : (string)$next;
            return $m[1] . $m[2] . $batch;
        }
        // 新生成：年(2位)月日时 + 2位随机大写字母(去 I,O,S,Z) + 01
        $alphabet = 'ABCDEFGHJKLMNPQRTUVWXY';
        $len = strlen($alphabet);
        $letters = $alphabet[random_int(0, $len - 1)] . $alphabet[random_int(0, $len - 1)];
        return date('ymdH') . $letters . '01';
    }

    public function testAddOrEditBatch(): void
    {
        $items = [];

        $data = new SalarySubsetData();
        $data->setStaffId("630143787")
            ->setCustomFields([
                'extsalaryMonth_614181_2006513888' => '2025-08',
                'extcurrencySymbol_614181_852666027' => 'CNY',
                'exttotalSalary_614181_1689881326' => 25906.51,
                'extbasicSalary_614181_1204972321' => 7000,
                'extbusinessSalary_614181_397273561' => 15426.51,
                'extmanagerSalary_614181_1299076106' => 0,
                'extapplyBonus_614181_443680259' => 0,
                'extrankingReward_614181_954614424' => 1000,
                'extonlinePushSalary_614181_2084683026' => 0,
                'extpromotionReward_614181_700194380' => 300,
                'extpayrentReward_614181_441079462' => 180,
            ]);
        $items[] = $data;

        $data = new SalarySubsetData();
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
        $items[] = $data;

        $result = $this->api->addOrEditBatch(Constants::SALARY_SUBSET_PRESET_1, $items);
        var_dump($result);
        $this->assertTrue($result->isSuccess());
    }

}
