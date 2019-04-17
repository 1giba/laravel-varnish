<?php

namespace Spatie\Varnish\Middleware;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Closure;

class CacheWithVarnish
{
    public function handle($request, Closure $next, int $cacheTimeInMinutes = null)
    {
        $response = $next($request);

        if ($response instanceof BinaryFileResponse) {
            $response->headers->set(config('varnish.cacheable_header_name'), '1');
            $response->headers->set(
                'Cache-Control', 'public,
                max-age='. 60 * ($cacheTimeInMinutes ?? config('varnish.cache_time_in_minutes'))
            );
            return $response;
        }

        return $response->withHeaders([
            config('varnish.cacheable_header_name') => '1',
            'Cache-Control' => 'public, max-age='. 60 * ($cacheTimeInMinutes ?? config('varnish.cache_time_in_minutes')),
        ]);
    }
}
