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

namespace ITalentOpenSDK\Http;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware as HttpMiddleware;
use ITalentOpenSDK\Auth\TokenStrategyInterface;
use ITalentOpenSDK\Constants;
use ITalentOpenSDK\Http\Response as HttpResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Middleware
{
    public static function useragent(): callable
    {
        return HttpMiddleware::mapRequest(fn (RequestInterface $request) => $request->withHeader(
            'User-Agent',
            sprintf(
                'ITalentOpenSDK (%s %s; %s) Client/%s PHP/%s',
                \PHP_OS,
                php_uname('r'),
                php_uname('m'),
                Constants::SDK_VERSION,
                \PHP_VERSION
            )
        ));
    }

    public static function auth(TokenStrategyInterface $token): callable
    {
        return HttpMiddleware::mapRequest(function (RequestInterface $request) use ($token) {
            $accessToken = $token->getAccessToken();
            return $request->withHeader('Authorization', 'Bearer ' . $accessToken);
        });
    }

    public static function log(LoggerInterface $logger, string $level = LogLevel::INFO, string $format = MessageFormatter::CLF): callable
    {
        return HttpMiddleware::log(
            $logger,
            new MessageFormatter($format),
            $level
        );
    }

    public static function retry(LoggerInterface $logger): callable
    {
        return HttpMiddleware::retry(function (
            int                $retries,
            RequestInterface   $request,
            ?ResponseInterface $response = null,
            ?\Throwable        $exception = null
        ) use ($logger) {
            if ($retries >= Constants::SDK_RETRY_MAX_RETRIES) {
                return false;
            }

            // 检查是否应该重试
            $shouldRetry = false;

            // 连接异常应该重试
            if ($exception instanceof ConnectException) {
                $shouldRetry = true;
            } // 5xx服务器错误应该重试
            elseif ($response !== null && $response->getStatusCode() >= 500) {
                $shouldRetry = true;
            }

            if ($shouldRetry) {
                $logger->warning(
                    sprintf(
                        'Retrying %s %s %s/%s, %s',
                        $request->getMethod(),
                        $request->getUri(),
                        $retries + 1,
                        Constants::SDK_RETRY_MAX_RETRIES,
                        $response !== null
                            ? 'status code: ' . $response->getStatusCode()
                            : ($exception !== null ? $exception->getMessage() : 'Unknown error')
                    ),
                    [
                        'host' => $request->getUri()->getHost(),
                    ]
                );

                return true;
            }

            return false;
        });
    }

    public static function response(): callable
    {
        return HttpMiddleware::mapResponse(fn (ResponseInterface $response) => new HttpResponse(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        ));
    }
}
