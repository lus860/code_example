<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MiddlewareCORS
{
    /**
     * @var bool
     */
    private $allowCredentials;
    /**
     * @var int
     */
    private $maxAge;
    /**
     * @var string[]
     */
    private $exposeHeaders;
    /**
     * @var string[]
     */
    private $headers  = [
        'origin' => 'Access-Control-Allow-Origin',
        'Access-Control-Request-Headers' => 'Access-Control-Allow-Headers',
        'Access-Control-Request-Method' => 'Access-Control-Allow-Methods'
    ];
    /**
     * @var string[]
     */
    private $allowOrigins;

    public function __construct()
    {
        $this->allowCredentials = true;
        $this->maxAge = 600;
        $this->exposeHeaders = ['HEADER1', 'HEADER2'];
        $this->allowOrigins = ['https://google.com', 'https://yandex.ru', 'http://localhost:8080'];
    }

    public function handle(Request $request, Closure $next)
    {
        if (
            !empty($this->allowOrigins)
            && $request->hasHeader('origin')
            && !in_array($request->header('origin'), $this->allowOrigins)
        ) {
            return new JsonResponse("origin: {$request->header('origin')} not allowed");
        }
        if ($request->hasHeader('origin')
            && $request->isMethod(Request::METHOD_OPTIONS)) {
            $response = new JsonResponse('cors pre response');
        } else {
            $response = $next($request);
        }
        foreach ($this->headers as $key => $value) {
            if ($request->hasHeader($key)) {
                $response->header($value, $request->header($key));
            }
        }
        $response->header('Access-Control-Max-Age', $this->maxAge);
        $response->header('Access-Control-Allow-Credentials', $this->allowCredentials);
        $response->header('Access-Control-Expose-Headers', implode(', ', $this->exposeHeaders));
        return $response;
    }

}