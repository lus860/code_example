<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $input = $request->all();
        $rules = ['email' => "required|email"];

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            \Response::json(array('success' => false, "status" => 400, "message" => $validator->errors()->first(), "data" => array()));
        } else {
            try {
                $response = Password::sendResetLink(['email' => $request->email]);
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return \Response::json(array('success' => true, "status" => 200, "message" => trans($response), "data" => array()));
                    case Password::INVALID_USER:
                        return \Response::json(array('success' => false, "status" => 400, "message" => trans($response), "data" => array()));
                }
                return response()->json([
                    'success' => true,
                    "message" => __('Reset password link sent on your email id.'),
                ], Response::HTTP_OK);
            } catch (\Swift_TransportException $ex) {
                return \Response::json(array('success' => false, "status" => 400, "message" => $ex->getMessage(), "data" => []));
            } catch (Exception $ex) {
                return \Response::json(array('success' => false, "status" => 400, "message" => $ex->getMessage(), "data" => []));
            }
        }
    }

    public function reset(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return self::httpBadRequest('Please enter a password which is not similar then current password.');
        }

        $reset_password_status = Password::reset($request->validated(), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($reset_password_status == Password::PASSWORD_RESET) {
            return response([
                'success' => true,
                'message' => __('Password updated successfully.'),
            ], Response::HTTP_OK);
        }

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return self::httpBadRequest('Invalid token provided.');
        }

        return response([
            'message' => __($reset_password_status)
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function httpBadRequest($error_message, $status = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'success' => false,
            'error_code' => $status,
            'error_message' => __($error_message)
        ], $status);
    }

}
