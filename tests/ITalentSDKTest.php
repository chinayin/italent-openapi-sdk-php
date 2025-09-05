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

use ITalentOpenSDK\Api\OpenApi;
use ITalentOpenSDK\Auth\ITalentTokenManager;
use ITalentOpenSDK\Auth\TokenStrategyInterface;
use ITalentOpenSDK\Http\HttpClient;
use ITalentOpenSDK\ITalentSDK;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ITalentSDKTest extends TestCase
{
    public function testLoggerServiceRegistration(): void
    {
        $this->assertTrue($this->sdk->has('logger'));
        $logger = $this->sdk->get('logger');

        $this->assertInstanceOf(LoggerInterface::class, $logger);
        $this->assertInstanceOf(Logger::class, $logger);
    }

    public function testHttpClientServiceRegistration(): void
    {
        $this->assertTrue($this->sdk->has('http_client'));
        $httpClient = $this->sdk->get('http_client');

        $this->assertInstanceOf(HttpClient::class, $httpClient);
    }

    public function testHttpClientWithTokenServiceRegistration(): void
    {
        $this->assertTrue($this->sdk->has('http_client_with_token'));
        $httpClientWithToken = $this->sdk->get('http_client_with_token');

        $this->assertInstanceOf(HttpClient::class, $httpClientWithToken);
    }

    public function testCacheServiceRegistration(): void
    {
        $this->assertTrue($this->sdk->has('cache'));
        $cache = $this->sdk->get('cache');

        $this->assertInstanceOf(CacheItemPoolInterface::class, $cache);
        $this->assertInstanceOf(FilesystemAdapter::class, $cache);
    }

    public function testTokenServiceRegistration(): void
    {
        $this->assertTrue($this->sdk->has('token'));
        $token = $this->sdk->get('token');

        $this->assertInstanceOf(TokenStrategyInterface::class, $token);
        $this->assertInstanceOf(ITalentTokenManager::class, $token);
    }

    public function testTokenService(): void
    {
        /** @var TokenStrategyInterface $token */
        $token = $this->sdk->get('token');
        $accessToken = null;
        try {
            $accessToken = $token->getAccessToken();
        } catch (\Exception $e) {
            var_dump($e);
        }
        $this->assertNotEmpty($accessToken);
    }

    public function testApiServiceRegistration(): void
    {
        $this->assertTrue($this->sdk->has('openapi'));
        $openapi = $this->sdk->get('openapi');

        $this->assertInstanceOf(OpenApi::class, $openapi);
    }

    public function testAllServicesAreRegistered(): void
    {
        $expectedServices = [
            'logger',
            'logger_handler',
            'client',
            'http_client',
            'client_with_token',
            'http_client_with_token',
            'cache',
            'token',
        ];

        foreach ($expectedServices as $service) {
            $this->assertTrue($this->sdk->has($service), "Service '{$service}' should be registered");
        }
    }

    public function testLoggerTimezone(): void
    {
        $logger = $this->sdk->get('logger');
        $this->assertInstanceOf(Logger::class, $logger);

        // Verify timezone is set to PRC
        $timezone = $logger->getTimezone();
        $this->assertEquals('PRC', $timezone->getName());
    }

    public function testCustomHandlerRegistration(): void
    {
        $runtimeDir = dirname(__DIR__) . '/runtime';
        $logFile = $runtimeDir . '/log/custom.log';

        // 创建自定义 Handler
        $customHandler = new RotatingFileHandler($logFile, 5, Logger::DEBUG);

        // 使用自定义 Handler 创建 SDK
        $config = [
            'app_key' => 'test_key',
            'app_secret' => 'test_secret',
            'log' => [
                'handler' => $customHandler,
                'timezone' => 'PRC',
            ],
        ];

        $sdk = new ITalentSDK($config);
        $logger = $sdk->get('logger');

        $this->assertInstanceOf(Logger::class, $logger);

        // 验证 Handler 是否正确设置
        $handlers = $logger->getHandlers();
        $this->assertCount(1, $handlers);
        $this->assertInstanceOf(RotatingFileHandler::class, $handlers[0]);

        // 验证时区设置
        $timezone = $logger->getTimezone();
        $this->assertEquals('PRC', $timezone->getName());
    }

}
