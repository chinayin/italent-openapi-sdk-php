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
 * 搜索过滤器，用于构建各种常用的搜索参数
 */
class SearchFilter extends Model
{
    /** @var array|null 业务OId集合，示例：[116347665,116347666,116347667]。正整数，必填，元素个数小于等于300个 */
    private ?array $oIds = null;

    /** @var string|null 时间范围开始时间，格式：2021-01-01T00:00:00 */
    private ?string $startTime = null;

    /** @var string|null 时间范围结束时间，格式：2021-01-02T00:00:00 */
    private ?string $stopTime = null;

    /** @var string|null 时间窗查询类型，1修改时间、2业务修改时间 */
    private ?string $timeWindowQueryType = null;

    /** @var string|null ScrollId，第一次查询为空，后续为上次结果返回的ScrollId */
    private ?string $scrollId = null;

    /** @var int|null 每批次查询个数，默认100个 */
    private ?int $capacity = null;

    /** @var bool|null 是否包括已删除数据，默认否 */
    private ?bool $isWithDeleted = null;

    /** @var array|null 查询字段列表，默认为null(获取全部信息) */
    private ?array $columns = null;

    /** @var array|null 排序列名字典，Key为字段编码，Value为排序方式
     * 排序列名字典，Key为字段编码，Value为排序方式，多个排序条件的话，从前往后依次排序。
     * 0默认不排序、1升序、2降序。示例：{"Name":"1","Age":"2"}
     */
    private ?array $sort = null;

    /** @var array|null 自定义字段查询条件
     * 自定义字段查询条件，多个条件使用and且关系，不支持or或关系。
     * 示例：[{"fieldName": "extExtQueryFloat_127666_832132060","queryType": 5,"values": ["1"]}]
     */
    private ?array $extQueries = null;

    /** @var array 额外的自定义参数 */
    private array $extraParams = [];

    public function setOIds(array $oIds): self
    {
        $this->oIds = $oIds;
        return $this;
    }

    public function getOIds(): ?array
    {
        return $this->oIds;
    }

    public function setStartTime(string $startTime): self
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function setStopTime(string $stopTime): self
    {
        $this->stopTime = $stopTime;
        return $this;
    }

    public function getStopTime(): ?string
    {
        return $this->stopTime;
    }

    public function setTimeWindowQueryType(string $timeWindowQueryType): self
    {
        $this->timeWindowQueryType = $timeWindowQueryType;
        return $this;
    }

    public function getTimeWindowQueryType(): ?string
    {
        return $this->timeWindowQueryType;
    }

    public function setScrollId(string $scrollId): self
    {
        $this->scrollId = $scrollId;
        return $this;
    }

    public function getScrollId(): ?string
    {
        return $this->scrollId;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;
        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setSort(array $sort): self
    {
        $this->sort = $sort;
        return $this;
    }

    public function getSort(): ?array
    {
        return $this->sort;
    }

    public function addSort(string $field, string $direction): self
    {
        if ($this->sort === null) {
            $this->sort = [];
        }
        $this->sort[$field] = $direction;
        return $this;
    }

    public function setExtQueries(array $extQueries): self
    {
        $this->extQueries = $extQueries;
        return $this;
    }

    public function getExtQueries(): ?array
    {
        return $this->extQueries;
    }

    public function addExtQuery(string $fieldName, int $queryType, array $values): self
    {
        if ($this->extQueries === null) {
            $this->extQueries = [];
        }
        $this->extQueries[] = [
            'fieldName' => $fieldName,
            'queryType' => $queryType,
            'values' => $values,
        ];
        return $this;
    }

    public function setIsWithDeleted(bool $isWithDeleted): self
    {
        $this->isWithDeleted = $isWithDeleted;
        return $this;
    }

    public function getIsWithDeleted(): ?bool
    {
        return $this->isWithDeleted;
    }

    public function setColumns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function getColumns(): ?array
    {
        return $this->columns;
    }

    /**
     * 设置额外的自定义参数
     */
    public function setExtraParam(string $key, $value): self
    {
        $this->extraParams[$key] = $value;
        return $this;
    }

    /**
     * 获取额外的自定义参数
     */
    public function getExtraParam(string $key)
    {
        return $this->extraParams[$key] ?? null;
    }

    /**
     * 获取所有额外参数
     */
    public function getExtraParams(): array
    {
        return $this->extraParams;
    }

    /**
     * 转换为数组，包含所有非空参数和额外参数
     */
    public function toArray(): array
    {
        $result = [];

        // 添加标准参数
        if ($this->oIds !== null) {
            $result['oIds'] = $this->oIds;
        }
        if ($this->startTime !== null) {
            $result['startTime'] = $this->startTime;
        }
        if ($this->stopTime !== null) {
            $result['stopTime'] = $this->stopTime;
        }
        if ($this->isWithDeleted !== null) {
            $result['isWithDeleted'] = $this->isWithDeleted;
        }
        if ($this->columns !== null) {
            $result['columns'] = $this->columns;
        }
        if ($this->scrollId !== null) {
            $result['scrollId'] = $this->scrollId;
        }
        if ($this->capacity !== null) {
            $result['capacity'] = $this->capacity;
        }
        if ($this->timeWindowQueryType !== null) {
            $result['timeWindowQueryType'] = $this->timeWindowQueryType;
        }
        if ($this->sort !== null) {
            $result['sort'] = $this->sort;
        }
        if ($this->extQueries !== null) {
            $result['extQueries'] = $this->extQueries;
        }

        // 合并额外参数
        return array_merge($result, $this->extraParams);
    }
}
