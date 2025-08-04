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

use ITalentOpenSDK\Model\EmployeeSubsetData;
use ITalentOpenSDK\Model\ScrollResult;
use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Traits\HttpClientTrait;

/**
 *  员工子集
 */
class EmployeeSubset
{
    use HttpClientTrait;

    /** @var int 文档中的版本号，为了统一升级版本 */
    private int $version = 5;

    /**
     * 根据员工子集对象ID集合获取指定的员工子集相关信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=46f1f7b1-eb7a-473e-8312-7a1bdeaeae15
     */
    public function getByTimeWindow(string $metaObjectName, ?SearchFilter $filter = null): ScrollResult
    {
        $filter ??= new SearchFilter();
        $filter->setExtraParam('metaObjectName', $metaObjectName);

        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/EmpSubset/GetByTimeWindow", $filter->toArray());
        return ScrollResult::fromArray($r);
    }

    /**
     * 根据员工子集对象ID集合获取指定的员工子集相关信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=24e257de-3897-4b2c-b951-cbcafa608582
     */
    public function getSubsetByIds(string $metaObjectName, array $oIds, ?SearchFilter $filter = null): ScrollResult
    {
        $filter ??= new SearchFilter();
        $filter->setExtraParam('metaObjectName', $metaObjectName);
        $filter->setExtraParam('ids', $oIds);

        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/EmpSubset/GetSubsetByIds", $filter->toArray());
        return ScrollResult::fromArray($r);
    }

    /**
     * 根据员工UserID获取指定员工的指定员工子集相关信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=01798fc7-5528-488b-afc6-dbe1bc18bde7
     */
    public function getSubsetByUserId(string $metaObjectName, int $oId, ?SearchFilter $filter = null): ScrollResult
    {
        $filter ??= new SearchFilter();
        $filter->setExtraParam('metaObjectName', $metaObjectName);
        $filter->setExtraParam('oId', $oId);

        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/EmpSubset/GetSubsetByUserId", $filter->toArray());
        return ScrollResult::fromArray($r);
    }

    /**
     * 新建指定员工的指定子集对象相关信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=28e6a193-5ab1-4299-aec4-518f048010c0
     */
    public function create(string $metaObjectName, EmployeeSubsetData $data): ?string
    {
        $r = $this->httpClient->postJson(
            "TenantBaseExternal/api/v{$this->version}/EmpSubset/Create",
            ['metaObjectName' => $metaObjectName, 'data' => $data->toArray()]
        );
        // objectId
        return $r['data'] ?? null;
    }

    /**
     * 部分更新指定子集对象相关信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=961f66c4-943c-4744-b00f-cbdb35d824f2
     */
    public function update(string $metaObjectName, EmployeeSubsetData $data): bool
    {
        $r = $this->httpClient->postJson(
            "TenantBaseExternal/api/v{$this->version}/EmpSubset/Update",
            ['metaObjectName' => $metaObjectName, 'data' => $data->toArray()]
        );
        return true;
    }

}
