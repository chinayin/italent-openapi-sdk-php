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

use ITalentOpenSDK\Model\OperationResult;
use ITalentOpenSDK\Model\SalarySubsetData;
use ITalentOpenSDK\Traits\HttpClientTrait;

/**
 *  预置薪酬子集
 */
class PresetSalarySubset
{
    use HttpClientTrait;

    /** @var int 文档中的版本号，为了统一升级版本 */
    private int $version = 2;

    /**
     * 添加或修改预置薪酬子集
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=ca13de1c-b945-4615-8934-e29d2a96a638
     */
    public function addOrEdit(string $metaObjectName, SalarySubsetData $data): OperationResult
    {
        $r = $this->httpClient->postJson(
            "compensationv2/v{$this->version}/PresetSalarySubset/AddOrEdit",
            ['presetSalarySubsetCode' => $metaObjectName, 'models' => [$data->toArray()]]
        );
        return OperationResult::fromArray($r);
    }

    /**
     * 添加或修改预置薪酬子集(批量)
     *
     * @link https://open.italent.cn/#/open-document?menu=document-center&id=ca13de1c-b945-4615-8934-e29d2a96a638
     */
    public function addOrEditBatch(string $metaObjectName, array $items): OperationResult
    {
        $models = [];
        foreach ($items as $item) {
            if ($item instanceof SalarySubsetData) {
                $models[] = $item->toArray();
            }
        }
        $r = $this->httpClient->postJson(
            "compensationv2/v{$this->version}/PresetSalarySubset/AddOrEdit",
            ['presetSalarySubsetCode' => $metaObjectName, 'models' => $models]
        );
        return OperationResult::fromArray($r);
    }

}
