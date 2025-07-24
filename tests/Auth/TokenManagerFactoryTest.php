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

namespace ITalentOpenSDK\Tests\Auth;

use ITalentOpenSDK\Auth\ITalentTokenManager;
use ITalentOpenSDK\Auth\TokenManagerFactory;
use ITalentOpenSDK\Http\HttpClientInterface;
use ITalentOpenSDK\Tests\TestCase;
use Mockery;
use Psr\Cache\CacheItemPoolInterface;

class TokenManagerFactoryTest extends TestCase
{
    private $mockCache;
    private $mockHttpClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockCache = Mockery::mock(CacheItemPoolInterface::class);
        $this->mockHttpClient = Mockery::mock(HttpClientInterface::class);
    }

    public function testCreateITalentTokenManager(): void
    {
        $tokenManager = TokenManagerFactory::createITalentTokenManager(
            'test_app_key',
            'test_app_secret',
            $this->mockCache,
            $this->mockHttpClient
        );

        $this->assertInstanceOf(ITalentTokenManager::class, $tokenManager);
        $this->assertEquals('test_app_key', $tokenManager->getAppKey());
        $this->assertEquals('test_app_secret', $tokenManager->getAppSecret());
    }

    public function testCreateFromConfigWithITalentType(): void
    {
        $config = [
            'type' => 'italent',
            'app_key' => 'config_app_key',
            'app_secret' => 'config_app_secret',
        ];

        $tokenManager = TokenManagerFactory::createFromConfig(
            $config,
            $this->mockCache,
            $this->mockHttpClient
        );

        $this->assertInstanceOf(ITalentTokenManager::class, $tokenManager);
    }

    public function testCreateFromConfigWithDefaultType(): void
    {
        $config = [
            'app_key' => 'default_app_key',
            'app_secret' => 'default_app_secret',
        ];

        $tokenManager = TokenManagerFactory::createFromConfig(
            $config,
            $this->mockCache,
            $this->mockHttpClient
        );

        $this->assertInstanceOf(ITalentTokenManager::class, $tokenManager);
    }

    public function testCreateFromConfigThrowsExceptionForUnsupportedType(): void
    {
        $config = [
            'type' => 'unsupported_type',
            'app_key' => 'test_key',
            'app_secret' => 'test_secret',
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported token manager type: unsupported_type');

        TokenManagerFactory::createFromConfig(
            $config,
            $this->mockCache,
            $this->mockHttpClient
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
