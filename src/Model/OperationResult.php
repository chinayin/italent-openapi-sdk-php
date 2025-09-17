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

class OperationResult extends Model
{
    private bool $success;
    private string $code;
    private string $message;
    private ?array $data;

    public static function fromArray(array $data): self
    {
        $self = new self();
        $self->code = (string)($data['code'] ?? '');
        $self->message = $data['message'] ?? '';
        $self->data = $data['data'] ?? null;
        $self->success = $self->code === '200';
        return $self;
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

}
