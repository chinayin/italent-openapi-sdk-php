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
 *  假期余额
 */
class VacationRemain
{
    use HttpClientTrait;

    /** @var int 文档中的版本号，为了统一升级版本 */
    private int $version = 1;

    /**
     * 通过UserId获取假期余额数据
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=051b307a-dd87-48c6-a197-94207fbb40f9
     */
    public function getListByUserId(int $userId, int $pageIndex = 1, int $pageSize = 100): ScrollResult
    {
        $r = $this->httpClient->postJson("AttendanceOpen/api/v{$this->version}/VacationRemain/GetListByUserId", [
            'UserId' => $userId,
            'PageIndex' => $pageIndex,
            'PageSize' => $pageSize,
        ]);
        return ScrollResult::fromArray([
            'data' => $r['Data']['VacationRemainList'] ?? [],
            'total' => $r['Data']['Total'] ?? null,
        ]);
    }

    /**
     * 获取假期余额数据
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=592ada78-83cd-49e8-8bcc-2013ba4a727e
     */
    public function getVacationRemainList(SearchFilter $searchFilter): ScrollResult
    {
        $r = $this->httpClient->postJson("AttendanceOpen/api/v{$this->version}/VacationRemain/GetVacationRemainList", $searchFilter->toArray());
        return ScrollResult::fromArray([
            'data' => $r['data']['vacationRemainList'] ?? [],
            'total' => $r['data']['total'] ?? null,
            'scrollId' => $r['data']['sortCursor'] ?? '',
        ]);
    }
}
