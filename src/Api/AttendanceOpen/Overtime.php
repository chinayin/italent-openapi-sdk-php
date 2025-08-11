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
 *  加班
 */
class Overtime
{
    use HttpClientTrait;

    /** @var int 文档中的版本号，为了统一升级版本 */
    private int $version = 1;

    /**
     * 获取员工的安排加班的数据
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=54d8e0ef-1f23-43b2-b246-c4fc4fb6a265
     */
    public function getScheduledOverTimeRangeList(array $userIds, string $startDate, string $stopDate): ScrollResult
    {
        $filter = new SearchFilter();
        $filter->addExtraParam('staffIds', $userIds);
        $filter->addExtraParam('startDate', $startDate);
        $filter->addExtraParam('stopDate', $stopDate);

        $r = $this->httpClient->postJson("AttendanceOpen/api/v{$this->version}/AttendanceOvertime/GetScheduledOverTimeRangeList", $filter->toArray());
        return ScrollResult::fromArray([
            'data' => $r['data']['vacationList'] ?? [],
            'total' => $r['data']['total'] ?? null,
            'scrollId' => $r['data']['sortCursor'] ?? '',
        ]);
    }

    /**
     * 获取加班数据
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=277b62cf-114f-4946-8524-3f732fbd8c90
     */
    public function getOverTimeListByDate(string $day, ?string $queryCursor = null, int $pageSize = 100): ScrollResult
    {
        $r = $this->httpClient->postJson("AttendanceOpen/api/v{$this->version}/AttendanceOvertime/GetOverTimeListByDate", [
            'overTimeDate' => $day,
            'queryCursor' => $queryCursor,
            'pageSize' => $pageSize,
        ]);
        return ScrollResult::fromArray([
            'data' => $r['data']['overTimeList'] ?? [],
            'total' => $r['data']['total'] ?? null,
            'scrollId' => $r['data']['sortCursor'] ?? '',
        ]);
    }

    /**
     * 按照审批通过时间获取加班数据
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=51008470-aa6d-483c-a552-9390410c2d4b
     */
    public function getApprovalCompletedOverTimeListByDateTime(SearchFilter $filter, ?string $queryCursor = null, int $pageSize = 100): ScrollResult
    {
        if ($queryCursor !== null) {
            $filter->addExtraParam('queryCursor', $queryCursor);
        }
        $filter->addExtraParam('pageSize', $pageSize);
        $r = $this->httpClient->postJson("AttendanceOpen/api/v{$this->version}/AttendanceOvertime/GetApprovalCompletedOverTimeListByDateTime", $filter->toArray());
        return ScrollResult::fromArray([
            'data' => $r['data']['overTimeList'] ?? [],
            'total' => $r['data']['total'] ?? null,
            'scrollId' => $r['data']['sortCursor'] ?? '',
        ]);
    }
}
