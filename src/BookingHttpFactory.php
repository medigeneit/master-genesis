<?php

namespace Medigeneit\MasterGenesis;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;


/**
 * @method \Illuminate\Http\Client\PendingRequest accept(string $contentType)
 * @method \Illuminate\Http\Client\PendingRequest acceptJson()
 * @method \Illuminate\Http\Client\PendingRequest asForm()
 * @method \Illuminate\Http\Client\PendingRequest asJson()
 * @method \Illuminate\Http\Client\PendingRequest asMultipart()
 * @method \Illuminate\Http\Client\PendingRequest async()
 * @method \Illuminate\Http\Client\PendingRequest attach(string|array $name, string|resource $contents = '', string|null $filename = null, array $headers = [])
 * @method \Illuminate\Http\Client\PendingRequest baseUrl(string $url)
 * @method \Illuminate\Http\Client\PendingRequest beforeSending(callable $callback)
 * @method \Illuminate\Http\Client\PendingRequest bodyFormat(string $format)
 * @method \Illuminate\Http\Client\PendingRequest contentType(string $contentType)
 * @method \Illuminate\Http\Client\PendingRequest dd()
 * @method \Illuminate\Http\Client\PendingRequest dump()
 * @method \Illuminate\Http\Client\PendingRequest retry(int $times, int $sleep = 0, ?callable $when = null)
 * @method \Illuminate\Http\Client\PendingRequest sink(string|resource $to)
 * @method \Illuminate\Http\Client\PendingRequest stub(callable $callback)
 * @method \Illuminate\Http\Client\PendingRequest timeout(int $seconds)
 * @method \Illuminate\Http\Client\PendingRequest withBasicAuth(string $username, string $password)
 * @method \Illuminate\Http\Client\PendingRequest withBody(resource|string $content, string $contentType)
 * @method \Illuminate\Http\Client\PendingRequest withCookies(array $cookies, string $domain)
 * @method \Illuminate\Http\Client\PendingRequest withDigestAuth(string $username, string $password)
 * @method \Illuminate\Http\Client\PendingRequest withHeaders(array $headers)
 * @method \Illuminate\Http\Client\PendingRequest withMiddleware(callable $middleware)
 * @method \Illuminate\Http\Client\PendingRequest withOptions(array $options)
 * @method \Illuminate\Http\Client\PendingRequest withToken(string $token, string $type = 'Bearer')
 * @method \Illuminate\Http\Client\PendingRequest withUserAgent(string $userAgent)
 * @method \Illuminate\Http\Client\PendingRequest withoutRedirecting()
 * @method \Illuminate\Http\Client\PendingRequest withoutVerifying()
 * @method array pool(callable $callback)
 * @method \Illuminate\Http\Client\Response delete(string $url, array $data = [])
 * @method \Illuminate\Http\Client\Response get(string $url, array|string|null $query = null)
 * @method \Illuminate\Http\Client\Response head(string $url, array|string|null $query = null)
 * @method \Illuminate\Http\Client\Response patch(string $url, array $data = [])
 * @method \Illuminate\Http\Client\Response post(string $url, array $data = [])
 * @method \Illuminate\Http\Client\Response put(string $url, array $data = [])
 * @method \Illuminate\Http\Client\Response send(string $method, string $url, array $options = [])
 *
 * @see \Illuminate\Http\Client\PendingRequest
 */
class BookingHttpFactory extends Factory
{


    /**
     * Create a new pending request instance for this factory.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    protected function newPendingRequest()
    {
        $pendingRequest = new PendingRequest($this);
        $pendingRequest->baseUrl(env('BOOKING_BASE_URL'));
        $pendingRequest->withToken(env('BOOKING_AUTH_TOKEN'));
        $pendingRequest->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
        return $pendingRequest;
    }
}
