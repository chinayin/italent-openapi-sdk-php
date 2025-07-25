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
 *  组织单元
 */
class Organization
{
    use HttpClientTrait;

    /** @var int 文档中的版本号，为了统一升级版本 */
    private int $version = 5;

    /**
     * 根据时间窗滚动查询变动的组织单元信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=dc1e628c-21a6-49cc-ae0c-7fc4a037d1bd
     */
    public function getByTimeWindow(SearchFilter $filter): ScrollResult
    {
        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/Organization/GetByTimeWindow", $filter->toArray());
        return ScrollResult::fromArray($r);
    }

    /**
     * 根据组织OId集合获取组织相关信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=c59e60ee-345a-4441-967c-631f8ace1adf
     */
    public function getByIds(array $oIds, ?SearchFilter $filter = null): ScrollResult
    {
        // 踩坑：这里的$oIds需要是array<int>
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
        $filter->setExtraParam('oIds', $oIds);

        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/Organization/GetByIds", $filter->toArray());
        return ScrollResult::fromArray($r);
    }

    /**
     * 根据组织Code获取组织信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=01a07830-98ba-4656-b168-b15016088898
     */
    public function getOrganizationInfoByCodes(array $codes, ?SearchFilter $filter = null): ScrollResult
    {
        if (count($codes) > 300) {
            throw new \InvalidArgumentException(
                sprintf(
                    'codes must contain at most %d items; got %d.',
                    300,
                    count($codes)
                ),
                -1
            );
        }
        $filter ??= new SearchFilter();
        $filter->setExtraParam('codes', $codes);

        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/Organization/GetOrganizationInfoByCodes", $filter->toArray());
        return ScrollResult::fromArray($r);
    }

}
