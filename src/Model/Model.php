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

class Model
{
    public function toArray(): array
    {
        return array_filter((array)$this, fn ($v) => null !== $v);
    }

    public function generateBody(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}
