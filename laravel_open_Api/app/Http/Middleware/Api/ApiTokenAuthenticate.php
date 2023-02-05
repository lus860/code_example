<?php

namespace App\Http\Middleware\Settings;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiTokenAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->api_token) {
            $company = Company::where('api_token', $request->api_token)->first();
            if ($company && $company->api) {
                return $next($request);
            } else {
                return response()->json([
                    "status" => "fail",
                    'code' => 4011,
                    'message' => __("Invalid API Key")
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                "status" => "fail",
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => __("Request missing api token")
            ], Response::HTTP_UNAUTHORIZED);
        }

    }
}
