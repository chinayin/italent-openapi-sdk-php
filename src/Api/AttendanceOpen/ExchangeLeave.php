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
 *  调休假
 */
class ExchangeLeave
{
    use HttpClientTrait;

    /** @var int 文档中的版本号，为了统一升级版本 */
    private int $version = 1;

    /**
     * 查询调休假明细列表
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=351ad768-eff4-4257-8d73-12f53c250c35
     */
    public function details(SearchFilter $filter): ScrollResult
    {
        $r = $this->httpClient->postJson("AttendanceOpen/api/v{$this->version}/ExchangeLeave/Details", $filter->toArray());
        return ScrollResult::fromArray([
            'data' => $r['Data']['ExchangeLeaveInfoList'] ?? [],
            'total' => $r['Data']['Total'] ?? null,
        ]);
    }

    /**
     * 查询员工当前期间可结算调休假
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=6ef0873b-9f4d-4686-b2c1-4842254ef0cb
     */
    public function querySettlementAdjustRemains(array $userIds): ScrollResult
    {
        $r = $this->httpClient->postJson("AttendanceOpen/api/v{$this->version}/ExchangeLeave/QuerySettlementAdjustRemains", [
            'staffIds' => $userIds,
        ]);
        return ScrollResult::fromArray([
            'data' => $r['data'] ?? [],
        ]);
    }

}
