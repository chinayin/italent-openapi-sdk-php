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
 * 员工子集数据传输对象
 */
class EmployeeSubsetData extends Model
{
    /** @var int|null 员工UserID，两个ID（UserId、UserOriginalId）必须有且仅有一个有值 */
    private ?int $userId = null;

    /** @var string|null 员工外部ID标识，第三方系统唯一标识ID（非北森系统），两个ID（UserId、UserOriginalId）必须有且仅有一个有值 */
    private ?string $userOriginalId = null;

    /** @var array|null 员工业务相关子集实体对象的标准字段数据字典表（字段名称-值），注意：自定义字段请放在CustomProperties中 */
    private ?array $fields = null;

    /** @var array|null 租户级别自定义字段Key（字段编码）-Value（字段值）字典 */
    private ?array $customProperties = null;

    /** @var array|null 非必填（可选）标准字段Key（字段编码）-Value（字段值）字典，暂不使用，仅预留方便后续扩展使用 */
    private ?array $sysProperties = null;

    /** @var string|null 业务对象实体主键GUID，示例：094ae643-2b5a-4e00-b99c-0e5aaf0e5c66 */
    private ?string $objectId = null;

    /** @var array|null (更新时才使用) 清空字段集合，示例：["Desc","Note"]。字段未传值或者null表示不更新该字段，
     * 如需清空，请添加对应字段名称(不区分大小写，且必填字段清空会自动忽略)的元素。
     * 注意：该列表优先级高，若同一个字段既赋值又清空，则优先清空。
     */
    private ?array $emptyFields = null;

    /**
     * 设置员工UserID
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        $this->userOriginalId = null; // 确保只有一个ID有值
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * 设置员工外部ID标识
     */
    public function setUserOriginalId(string $userOriginalId): self
    {
        $this->userOriginalId = $userOriginalId;
        $this->userId = null; // 确保只有一个ID有值
        return $this;
    }

    public function getUserOriginalId(): ?string
    {
        return $this->userOriginalId;
    }

    /**
     * 设置标准字段数据字典
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function getFields(): ?array
    {
        return $this->fields;
    }

    /**
     * 添加单个标准字段
     */
    public function addField(string $key, $value): self
    {
        if ($this->fields === null) {
            $this->fields = [];
        }
        $this->fields[$key] = $value;
        return $this;
    }

    /**
     * 设置系统属性字典
     */
    public function setSysProperties(array $sysProperties): self
    {
        $this->sysProperties = $sysProperties;
        return $this;
    }

    public function getSysProperties(): ?array
    {
        return $this->sysProperties;
    }

    /**
     * 添加单个系统属性
     */
    public function addSysProperty(string $key, $value): self
    {
        if ($this->sysProperties === null) {
            $this->sysProperties = [];
        }
        $this->sysProperties[$key] = $value;
        return $this;
    }

    /**
     * 设置自定义属性字典
     */
    public function setCustomProperties(array $customProperties): self
    {
        $this->customProperties = $customProperties;
        return $this;
    }

    public function getCustomProperties(): ?array
    {
        return $this->customProperties;
    }

    /**
     * 添加单个自定义属性
     */
    public function addCustomProperty(string $key, $value): self
    {
        if ($this->customProperties === null) {
            $this->customProperties = [];
        }
        $this->customProperties[$key] = $value;
        return $this;
    }

    public function setObjectId(string $objectId): self
    {
        $this->objectId = $objectId;
        return $this;
    }

    public function getObjectId(): ?string
    {
        return $this->objectId;
    }

    public function setEmptyFields(array $fields): self
    {
        $this->emptyFields = $fields;
        return $this;
    }

    public function getEmptyFields(): ?array
    {
        return $this->emptyFields;
    }

    public function toArray(): array
    {
        $result = [];

        // create
        if ($this->userId !== null) {
            $result['userId'] = $this->userId;
        }
        if ($this->userOriginalId !== null) {
            $result['userOriginalId'] = $this->userOriginalId;
        }
        if ($this->fields !== null) {
            $result['fields'] = $this->fields;
        }
        if ($this->sysProperties !== null) {
            $result['sysProperties'] = $this->sysProperties;
        }
        if ($this->customProperties !== null) {
            $result['customProperties'] = $this->customProperties;
        }
        // update
        if ($this->objectId !== null) {
            $result['objectId'] = $this->objectId;
        }
        if ($this->emptyFields !== null) {
            $result['emptyFields'] = $this->emptyFields;
        }

        return $result;
    }
}
