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
 *  休假
 */
class Vacation
{
    use HttpClientTrait;

    /** @var int 文档中的版本号，为了统一升级版本 */
    private int $version = 1;

    /**
     * 根据审批通过时间获取休假数据-新
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=6d35c02b-1150-4271-b573-fe87947911fd
     */
    public function getVacationInfoByApprovalTime(SearchFilter $filter, ?string $queryCursor = null, int $pageSize = 100): ScrollResult
    {
        if ($queryCursor !== null) {
            $filter->addExtraParam('queryCursor', $queryCursor);
        }
        $filter->addExtraParam('pageSize', $pageSize);
        $r = $this->httpClient->postJson("AttendanceOpen/api/v{$this->version}/Vacation/GetVacationInfoByApprovalTime", $filter->toArray());
        return ScrollResult::fromArray([
            'data' => $r['data']['vacationList'] ?? [],
            'total' => $r['data']['total'] ?? null,
            'scrollId' => $r['data']['sortCursor'] ?? '',
        ]);
    }

    /**
     * 获取休假数据-新
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=1b34c50e-21c9-4a5b-a6a7-34144c9c8540
     */
    public function getListByDate(string $day, ?string $queryCursor = null, int $pageSize = 100): ScrollResult
    {
        $r = $this->httpClient->postJson("AttendanceOpen/api/v{$this->version}/Vacation/GetListByDate", [
            'day' => $day,
            'queryCursor' => $queryCursor,
            'pageSize' => $pageSize,
        ]);
        return ScrollResult::fromArray([
            'data' => $r['data']['vacationList'] ?? [],
            'total' => $r['data']['total'] ?? null,
            'scrollId' => $r['data']['sortCursor'] ?? '',
        ]);
    }

    /**
     * 获取员工已对冲的休假信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=2a68ea6f-128b-4433-b746-6b3f52b1f052
     */
    public function getVacationMapping(array $userIds, string $startDate, string $stopDate): ScrollResult
    {
        $filter = new SearchFilter();
        $filter->addExtraParam('UserIds', $userIds);
        $filter->addExtraParam('StartDate', $startDate);
        $filter->addExtraParam('StopDate', $stopDate);

        $r = $this->httpClient->postJson("AttendanceOpen/api/v{$this->version}/Vacation/GetVacationMapping", $filter->toArray());
        return ScrollResult::fromArray([
            'data' => $r['Data']['VacationList'] ?? [],
            'total' => $r['Data']['Total'] ?? null,
        ]);
    }

    /**
     * 获取员工的休假记录信息(含每天休假时长)
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=cf83a7f6-8285-44ef-8748-2267823f7fce
     */
    public function getVacationMappingIncludeApproving(array $userIds, string $startDate, string $stopDate): ScrollResult
    {
        $filter = new SearchFilter();
        $filter->addExtraParam('userIds', $userIds);
        $filter->addExtraParam('startDate', $startDate);
        $filter->addExtraParam('stopDate', $stopDate);

        $r = $this->httpClient->postJson("AttendanceOpen/api/v{$this->version}/Vacation/GetVacationMappingIncludeApproving", $filter->toArray());
        return ScrollResult::fromArray([
            'data' => $r['data']['vacationList'] ?? [],
            'total' => $r['data']['total'] ?? null,
        ]);
    }
}
