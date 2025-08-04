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

namespace ITalentOpenSDK\Api\TenantBaseExternal;

use ITalentOpenSDK\Model\ScrollResult;
use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Traits\HttpClientTrait;

/**
 *  员工与任职
 */
class Employee
{
    use HttpClientTrait;

    /** @var int 文档中的版本号，为了统一升级版本 */
    private int $version = 5;

    /**
     * 根据员工Email获取员工UserID
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=4016483e-f4d3-44b2-85df-286a4b9b598a
     */
    public function getUserIDByEmail(string $email): ?string
    {
        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/Employee/GetUserIDByEmail", [
            'email' => $email,
        ]);
        return $r['data'] ?? null;
    }

    /**
     * 根据员工工号批量获取对应UserID
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=c9ef68e1-b475-4b19-bbdc-75d127b77dd3
     */
    public function getUserIDsByJobNumbers(array $jobNumbers): array
    {
        if (count($jobNumbers) > 30) {
            throw new \InvalidArgumentException(
                sprintf(
                    'jobNumbers must contain at most %d items; got %d.',
                    30,
                    count($jobNumbers)
                ),
                -1
            );
        }
        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/Employee/GetUserIDsByJobNumbers", [
            'jobNumbers' => $jobNumbers,
        ]);
        return $r['data'] ?? [];
    }

    /**
     * 根据员工手机号批量获取对应UserID
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=e5bef837-f405-480c-9511-169b5d5295d6
     */
    public function getUserIDsByMobiles(array $mobiles): array
    {
        if (count($mobiles) > 30) {
            throw new \InvalidArgumentException(
                sprintf(
                    'mobiles must contain at most %d items; got %d.',
                    30,
                    count($mobiles)
                ),
                -1
            );
        }
        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/Employee/GetUserIDsByMobilesV5", [
            'mobiles' => $mobiles,
        ]);
        return $r['data'] ?? [];
    }

    /**
     * 根据时间窗滚动查询变动的员工与单条任职信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=d2405033-0bb3-4574-ad82-b959799e8882
     */
    public function getByTimeWindow(SearchFilter $filter): ScrollResult
    {
        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/Employee/GetByTimeWindow", $filter->toArray());
        return ScrollResult::fromArray($r);
    }

    /**
     * 滚动查询指定组织下的员工与单条任职信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=e01aa14c-7dda-430f-be9d-0d9aafc25dca
     */
    public function getEmployeeOfOrganization(SearchFilter $filter): ScrollResult
    {
        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/Employee/GetEmployeeOfOrganization", $filter->toArray());
        return ScrollResult::fromArray($r);
    }

    /**
     * 根据员工UserID集合获取未删除的员工相关信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=18aa3dc5-7215-4455-8dc3-35919c3eee12
     */
    public function getBasicInfoByIds(array $oIds, ?SearchFilter $filter = null): ScrollResult
    {
        // 踩坑：这里的$oIds需要是array<int>，不能string报错。
        if (count($oIds) > 300) {
            throw new \InvalidArgumentException(
                sprintf(
                    'oIds must contain at most %d items; got %d.',
                    300,
                    count($oIds)
                ),
                -1
            );
        }
        $filter ??= new SearchFilter();
        $filter->addExtraParam('oIds', $oIds);

        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/Employee/GetBasicInfoByIds", $filter->toArray());
        return ScrollResult::fromArray($r);
    }

    /**
     * 根据员工UserID集合获取未删除的员工相关信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=2975ae38-dbc6-44da-9eb4-0e3a9b469e68
     */
    public function getJuniorById(int $oId, ?SearchFilter $filter = null): ScrollResult
    {
        $filter ??= new SearchFilter();
        $filter->addExtraParam('oId', $oId);

        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/Employee/GetJuniorById", $filter->toArray());
        return ScrollResult::fromArray($r);
    }

    /**
     * 根据员工UserID集合获取指定条件的任职记录相关信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=e0b9ab6d-0ca7-41cc-8104-6637961aee8d
     */
    public function getServiceInfoByIds(SearchFilter $filter): ScrollResult
    {
        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/Employee/GetServiceInfoByIds", $filter->toArray());
        return ScrollResult::fromArray($r);
    }

}
