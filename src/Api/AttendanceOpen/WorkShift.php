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

namespace ITalentOpenSDK\Api\AttendanceOpen;

use ITalentOpenSDK\Model\ScrollResult;
use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Traits\HttpClientTrait;

/**
 *  班次
 */
class WorkShift
{
    use HttpClientTrait;

    /** @var int 文档中的版本号，为了统一升级版本 */
    private int $version = 1;

    /**
     * 根据员工批量获取班次
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=d2668094-8e87-4bab-9f37-0f754b409ec9
     */
    public function batchGetWorkShiftByStaffIdDate(array $staffIds, string $startDate, string $stopDate): ScrollResult
    {
        $filter = new SearchFilter();
        $filter->addExtraParam('staffIds', $staffIds);
        $filter->addExtraParam('startDate', $startDate);
        $filter->addExtraParam('stopDate', $stopDate);

        $r = $this->httpClient->postJson(
            "AttendanceOpen/api/v{$this->version}/WorkShift/BatchGetWorkShiftByStaffIdDate",
            $filter->toArray()
        );
        return ScrollResult::fromArray([
            'data' => $r['data']['workShifts'] ?? [],
            'total' => $r['data']['total'] ?? null,
        ]);
    }

    /**
     * 根据员工ID获取班次
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=d2668094-8e87-4bab-9f37-0f754b409ec9
     */
    public function getWorkShiftByUserId(SearchFilter $filter, int $pageIndex = 1, int $pageSize = 100): array
    {
        $filter->addExtraParam('PageIndex', $pageIndex);
        $filter->addExtraParam('PageSize', $pageSize);

        $r = $this->httpClient->postJson(
            "AttendanceOpen/api/v{$this->version}/WorkShift/GetWorkShiftByUserId",
            $filter->toArray()
        );
        return $r['Data'];
    }

    /**
     * 根据考勤卡号和日期获取某人班次数据
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=af800a25-e91c-4cf9-915b-d8cee76ab73e
     */
    public function get(SearchFilter $filter, int $pageIndex = 1, int $pageSize = 100): array
    {
        $filter->addExtraParam('PageIndex', $pageIndex);
        $filter->addExtraParam('PageSize', $pageSize);

        $r = $this->httpClient->postJson(
            "AttendanceOpen/api/v{$this->version}/WorkShift/GetWorkShiftInfo",
            $filter->toArray()
        );
        return $r['Data'];
    }

    /**
     * 获取租户班次信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=0b01da7f-467e-48a2-afad-c1ffa57c1fc5
     */
    public function getWorkShiftInfo(int $pageIndex = 1, int $pageSize = 100): ScrollResult
    {
        $r = $this->httpClient->postJson("AttendanceOpen/api/v{$this->version}/WorkShift/GetWorkShiftInfo", [
            'PageIndex' => $pageIndex,
            'PageSize' => $pageSize,
        ]);

        return ScrollResult::fromArray([
            'data' => $r['Data']['WorkShiftInfos'] ?? [],
            'total' => $r['Data']['Total'] ?? null,
        ]);
    }

}
