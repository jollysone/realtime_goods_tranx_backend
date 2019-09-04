<?php

namespace App\Http\Middleware;

use Closure;

class CrossOrigin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Credentials', $this->getCredentials());
        $response->headers->set('Access-Control-Allow-Methods', implode(', ', ['GET', 'POST', 'PATCH', 'PUT', 'DELETE', 'OPTIONS']));
        $response->headers->set('Access-Control-Allow-Headers', implode(', ', [
            'Origin', 'Content-Type', 'Accept', 'Cookie', 'Authorization', 'X-Auth-Token', 'Auth-Token', 'X-Requested-With', 'X_Requested_With'
        ]));
        // $response->headers->set('Access-Control-Allow-Headers', '*'); // Safari 不支持
        $response->headers->set('Access-Control-Allow-Origin', $this->getOrigin($request));

        return $response;
    }

    /**
     * Access-Control-Allow-Credentials
     *
     * @return string
     */
    protected function getCredentials(): string
    {
        return config('http.cors.credentials') ? 'true' : 'false';
    }

    /**
     * Access-Control-Allow-Origin
     *
     * @param $requestwithCredentials
     * @return string
     */
    protected function getOrigin($request): string
    {
        $origin = config('http.cors.origin');
        if ($origin === '*') {
            return '*';
        }

        $requestOrigin = $request->headers->get('origin');
        if (in_array($requestOrigin, (array)$origin)) {
            return $requestOrigin;
        }

        return (string)'';
    }
}
