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

namespace ITalentOpenSDK\Model;

/**
 * 预置薪酬子集数据传输对象
 */
class SalarySubsetData extends Model
{
    /** @var string|null 员工ID */
    private ?string $staffId = null;

    /** @var string|null 开始日期，格式：Y-m-d H:i:s */
    private ?string $startDate = null;

    /** @var bool|null 是否失效 */
    private ?bool $isInvalid = null;

    /** @var bool|null 是否生效 */
    private ?bool $status = null;

    /** @var string|null 结束日期，格式：Y-m-d H:i:s */
    private ?string $stopDate = null;

    /** @var string|null 项目名称，预置数据源：项目1、项目2、项目3 */
    private ?string $itemName = null;

    /** @var float|null 数值 */
    private ?float $numericVal = null;

    /** @var string|null 备注 */
    private ?string $note = null;

    /** @var array|null 自定义字段，包括此请求模型未给出的系统字段和租户自定义字段，字典类型，格式为：字段编码：值 */
    private ?array $customFields = null;

    /**
     * 设置员工ID
     */
    public function setStaffId(string $staffId): self
    {
        $this->staffId = $staffId;
        return $this;
    }

    public function getStaffId(): ?string
    {
        return $this->staffId;
    }

    /**
     * 设置开始日期
     */
    public function setStartDate(string $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    /**
     * 设置是否失效
     */
    public function setIsInvalid(bool $isInvalid): self
    {
        $this->isInvalid = $isInvalid;
        return $this;
    }

    public function getIsInvalid(): ?bool
    {
        return $this->isInvalid;
    }

    /**
     * 设置是否生效
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * 设置结束日期
     */
    public function setStopDate(string $stopDate): self
    {
        $this->stopDate = $stopDate;
        return $this;
    }

    public function getStopDate(): ?string
    {
        return $this->stopDate;
    }

    /**
     * 设置项目名称
     */
    public function setItemName(string $itemName): self
    {
        $this->itemName = $itemName;
        return $this;
    }

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    /**
     * 设置数值
     */
    public function setNumericVal(float $numericVal): self
    {
        $this->numericVal = $numericVal;
        return $this;
    }

    public function getNumericVal(): ?float
    {
        return $this->numericVal;
    }

    /**
     * 设置备注
     */
    public function setNote(string $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * 设置自定义字段字典
     */
    public function setCustomFields(array $customFields): self
    {
        $this->customFields = $customFields;
        return $this;
    }

    public function getCustomFields(): ?array
    {
        return $this->customFields;
    }

    /**
     * 添加单个自定义字段
     */
    public function addCustomField(string $key, $value): self
    {
        if ($this->customFields === null) {
            $this->customFields = [];
        }
        $this->customFields[$key] = $value;
        return $this;
    }

    public function toArray(): array
    {
        $result = [];

        if ($this->staffId !== null) {
            $result['staffId'] = $this->staffId;
        }
        if ($this->startDate !== null) {
            $result['startDate'] = $this->startDate;
        }
        if ($this->isInvalid !== null) {
            $result['isInvalid'] = $this->isInvalid;
        }
        if ($this->status !== null) {
            $result['status'] = $this->status;
        }
        if ($this->stopDate !== null) {
            $result['stopDate'] = $this->stopDate;
        }
        if ($this->itemName !== null) {
            $result['itemName'] = $this->itemName;
        }
        if ($this->numericVal !== null) {
            $result['numericVal'] = $this->numericVal;
        }
        if ($this->note !== null) {
            $result['note'] = $this->note;
        }
        if ($this->customFields !== null) {
            $result['customFields'] = $this->customFields;
        }

        return $result;
    }
}
