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

class ScrollResult extends Model
{
    private string $scrollId;
    private int $total;
    private array $data;
    /** @deprecated 已废弃字段 */
    private bool $isLastData;

    public static function fromArray(array $data): self
    {
        $self = new self();
        $self->scrollId = $data['scrollId'] ?? '';
        $self->data = $data['data'] ?? [];
        $self->total = $data['total'] ?? count($self->data);
        $self->isLastData = $data['isLastData'] ?? false;
        return $self;
    }

    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'hasMore' => $this->hasMore(),
            'scrollId' => $this->scrollId,
            'data' => $this->data,
        ];
    }

    public function hasMore(): bool
    {
        return !empty($this->data);
    }

    public function scrollId(): string
    {
        return $this->scrollId;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function items(): array
    {
        return $this->data;
    }

}
