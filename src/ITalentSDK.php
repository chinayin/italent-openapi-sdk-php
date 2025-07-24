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

use Doctrine\Common\Collections\ArrayCollection;
use GuzzleHttp\Client;
use ITalentOpenSDK\Auth\TokenManagerFactory;
use ITalentOpenSDK\Http\ClientFactory;
use ITalentOpenSDK\Http\HttpClient;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ITalentSDK extends ContainerBuilder
{
    private ArrayCollection $config;

    private array $apiServices = [
        'openapi' => \ITalentOpenSDK\Api\OpenApi::class,
        'Employee' => \ITalentOpenSDK\Api\Employee::class,
        'Organization' => \ITalentOpenSDK\Api\Organization::class,
    ];

    public function __construct(array $config)
    {
        parent::__construct();
        $this->config = new ArrayCollection($config);
        $this->registerServices();
    }

    private function registerServices(): void
    {
        $this->registerLogger();
        $this->registerHttpClient();
        $this->registerCache();
        $this->registerToken();
        $this->registerHttpClientWithToken();
        foreach ($this->apiServices as $id => $class) {
            $this->registerApi($id, $class);
        }
    }

    private function registerLogger(): void
    {
        $log = $this->config->get('log');
        if (is_subclass_of($log, LoggerInterface::class)) {
            $this->register('logger', $log);
        } elseif ($log) {
            $this->register('logger_handler', StreamHandler::class)
                ->setArguments([$log['file'], $log['level'] ?? 'info']);
            $this->registerMonolog();
        } else {
            $this->register('logger_handler', NullHandler::class);
            $this->registerMonolog();
        }
    }

    private function registerMonolog(): void
    {
        $log = $this->config->get('log');
        $this->register('logger', Logger::class)
            ->addArgument('ITalentOpenSDK')
            ->addMethodCall('setTimezone', [new \DateTimeZone($log['timezone'] ?? Constants::SDK_LOGGER_TIMEZONE)])
            ->addMethodCall('pushHandler', [new Reference('logger_handler')]);
    }

    private function registerHttpClient(): void
    {
        $this->register('client', Client::class)
            ->setArguments([
                new Reference('logger'),
                null, // no token
                $this->config->toArray(),
            ])
            ->setFactory([ClientFactory::class, 'create']);
        $this->register('http_client', HttpClient::class)
            ->addArgument(new Reference('client'));
    }

    private function registerHttpClientWithToken(): void
    {
        $this->register('client_with_token', Client::class)
            ->setArguments([
                new Reference('logger'),
                new Reference('token'),
                $this->config->toArray(),
            ])
            ->setFactory([ClientFactory::class, 'create']);
        $this->register('http_client_with_token', HttpClient::class)
            ->addArgument(new Reference('client_with_token'));
    }

    private function registerCache(): void
    {
        $cache = $this->config->get('cache');
        if (is_subclass_of($cache, CacheItemPoolInterface::class)) {
            $this->register('cache', $cache);
        } else {
            $service = $this->register('cache', FilesystemAdapter::class);
            if ($cache && isset($cache['path'])) {
                $service->setArguments(['', 0, $cache['path']]);
            }
        }
    }

    private function registerToken(): void
    {
        $this->register('token')
            ->setFactory([TokenManagerFactory::class, 'createFromConfig'])
            ->setArguments([
                $this->config->toArray(),
                new Reference('cache'),
                new Reference('http_client'),
            ]);
    }

    private function registerApi(string $id, string $class): void
    {
        $api = $this->register($id, $class)
            ->addMethodCall('setHttpClient', [new Reference('http_client_with_token')]);
    }
}
