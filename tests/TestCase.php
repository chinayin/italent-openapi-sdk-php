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

namespace ITalentOpenSDK\Tests;

use ITalentOpenSDK\ITalentSDK;
use ReflectionClass;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public ITalentSDK $sdk;

    protected function setUp(): void
    {
        parent::setUp();
        $runtimeDir = __DIR__ . '/../runtime/italent_sdk_test';
        $this->sdk = new ITalentSDK([
            'app_key' => getenv('SDK_APP_KEY'),
            'app_secret' => getenv('SDK_APP_SECRET'),
            'cache' => [
                'path' => "$runtimeDir/cache",
            ],
            'log' => [
                'file' => "$runtimeDir/log/sdk.log",
                'level' => 'debug',
            ],
            'http' => [
                'debug' => false,
            ],
        ]);
    }

    protected function callMethod(object $object, string $name, array $args = [])
    {
        $class = new ReflectionClass($object);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
