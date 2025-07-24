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

namespace ITalentOpenSDK\Traits;

use Psr\Cache\CacheItemPoolInterface;

trait CacheTrait
{
    protected CacheItemPoolInterface $cache;

    public function setCache(CacheItemPoolInterface $cache): void
    {
        $this->cache = $cache;
    }
}
