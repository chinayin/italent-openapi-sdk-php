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
use ITalentOpenSDK\Traits\HttpClientTrait;

/**
 *  排版
 */
class WorkShiftRecord
{
    use HttpClientTrait;

    /** @var int 文档中的版本号，为了统一升级版本 */
    private int $version = 1;

    /**
     * 获取排班数据
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=d5639759-1606-4942-ad71-e95e4daa8109
     */
    public function getWorkShiftRecordListByMonth(string $month, string $queryCursor = null, int $pageSize = 100): ScrollResult
    {
        $r = $this->httpClient->postJson("AttendanceOpen/api/v{$this->version}/WorkShiftRecord/GetWorkShiftRecordListByMoth", [
            'month' => $month,
            'queryCursor' => $queryCursor,
            'pageSize' => $pageSize,
        ]);
        return ScrollResult::fromArray([
            'data' => $r['data']['workShiftRecordList'] ?? [],
            'total' => $r['data']['total'] ?? null,
            'scrollId' => $r['data']['sortCursor'] ?? '',
        ]);
    }

}
