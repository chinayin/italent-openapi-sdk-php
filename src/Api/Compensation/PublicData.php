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

namespace ITalentOpenSDK\Api\Compensation;

use ITalentOpenSDK\Model\ScrollResult;
use ITalentOpenSDK\Model\SearchFilter;
use ITalentOpenSDK\Traits\HttpClientTrait;

/**
 *  薪酬数据
 */
class PublicData
{
    use HttpClientTrait;

    /** @var int 文档中的版本号，为了统一升级版本 */
    private int $version = 2;

    /**
     * 获取发薪数据
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=c71530f0-9ff3-4a02-a60c-4ff6aaea2a04
     */
    public function searchByPayPeriod(SearchFilter $filter): ScrollResult
    {
        $r = $this->httpClient->postJson("compensationv2/v{$this->version}/PublicData/SearchByPayPeriod", $filter->toArray());
        return ScrollResult::fromArray($r);
    }
}
