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
 *  职务
 */
class JobPost
{
    use HttpClientTrait;

    /** @var int 文档中的版本号，为了统一升级版本 */
    private int $version = 5;

    /**
     * 根据时间窗滚动查询变动的职务信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=7c19ab15-5aa0-4268-9f72-3984566aa911
     */
    public function getByTimeWindow(SearchFilter $filter): ScrollResult
    {
        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/JobPost/GetByTimeWindow", $filter->toArray());
        return ScrollResult::fromArray($r);
    }

    /**
     * 通过职务OID获取职务信息
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=b1536410-e283-43ff-943d-a7e398d62d37
     */
    public function getByOIds(array $oIds, ?SearchFilter $filter = null): ScrollResult
    {
        if (count($oIds) > 300) {
            throw new \InvalidArgumentException(
                sprintf('oIds must contain at most %d items; got %d.', 300, count($oIds)),
                -1
            );
        }
        $filter ??= new SearchFilter();
        $filter->setExtraParam('oIds', $oIds);

        $r = $this->httpClient->postJson("TenantBaseExternal/api/v{$this->version}/JobPost/GetByOIds", $filter->toArray());
        return ScrollResult::fromArray($r);
    }

}
