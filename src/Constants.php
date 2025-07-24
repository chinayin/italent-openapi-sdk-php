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

namespace ITalentOpenSDK;

class Constants
{
    /** @var string sdk版本号 */
    public const SDK_VERSION = '1.0.0';
    /** @var string 调用base uri */
    public const SDK_BASE_URI = 'https://openapi.italent.cn/';
    /** @var int 重试次数 */
    public const SDK_RETRY_MAX_RETRIES = 1;
    /** @var string 日志时区 */
    public const SDK_LOGGER_TIMEZONE = "PRC";
}
