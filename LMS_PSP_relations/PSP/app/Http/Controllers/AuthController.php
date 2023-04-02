<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    const ACTIVE = 1;
    const NOT_ACTIVE = 0;
    public $log;

    public function __construct()
    {
        parent::__construct();
    }

    public function loginFromLMS(Request $request)
    {
        $company = \App\Models\Company::where('domain', $request->domain)->first();

        if ($company) {
            if ($company->active == Company::NOT_ACTIVE) {
                return self::httpBadRequest('Your company is not active');
            }

            if ($company->lms_company == Company::LMS_COMPANY_NOT_ACTIVE) {
                return self::httpBadRequest('Your company is not in the LMS');
            }

            $user = \App\Models\User::where('email', $request->email)->where('id_company', $company->id)->first();

            if ($user) {

                if ($user->active == self::NOT_ACTIVE) {
                    return self::httpBadRequest('User is inactive!');
                }

                $domain = $user->company->domain;

                if (password_verify($domain . $request->email, $request->token)) {

                    if (!$company->session_lifetime_forever && $company->session_lifetime) {
                        $token = auth()->setTTL($company->session_lifetime)->login($user);
                    } else {
                        $token = auth()->login($user);
                    }

                    if (!$token) {
                        return self::httpBadRequest('something went wrong');
                    }

                    unset($user->company);

                    return response()->json([
                        'token' => $token,
                        'user' => $user,
                        'company' => $company,
                    ], 200);
                }

                return response()->json([
                    'error' => 'Incorrect email/token/company',
                    'email' => $user->email,
                    'domain' => $domain,
                    'token' => $request->token,
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

        return self::httpBadRequest('not found', Response::HTTP_NOT_FOUND);
    }

    public static function httpBadRequest($error_message, $status = Response::HTTP_BAD_REQUEST)
    {
        if ($error_message == 'permission denied') {
            return response()->json([
                'success' => false,
                'error_code' => Response::HTTP_MULTIPLE_CHOICES,
                'error_message' => __($error_message)
            ], Response::HTTP_MULTIPLE_CHOICES);
        } else {
            return response()->json([
                'success' => false,
                'error_code' => $status,
                'error_message' => __($error_message)
            ], $status);
        }
    }

}
