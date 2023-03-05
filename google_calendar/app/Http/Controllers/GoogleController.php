<?php

namespace App\Http\Controllers;

use Google_Service_Calendar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;

class GoogleController extends AccountController
{
    /**
     * @return mixed
     */
    public function checkCalendarConnection()
    {
        $storageGoogleTokenPath = FileController::getStorageGoogleTokenPath($this->user->id, $this->company->id);

        return response()->json([
            'status' => true,
            'message' => __('Success'),
            'googleCalendarConnected' => Storage::exists($storageGoogleTokenPath),
            'userId' => $this->user->id
        ]);
    }

    /**
     * @return mixed
     */
    public function authUrl()
    {
        if (!$this->company->google_calendar_connected) {
            return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
        }

        $storageGoogleTokenPath = FileController::getStorageGoogleTokenPath($this->user->id, $this->company->id);

        $googleAuthUrl = null;
        if (!Storage::exists($storageGoogleTokenPath)) {
            $googleAuthUrl = $this->getGoogleAuthUrl();
        }

        if (!$googleAuthUrl) {
            return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
        }

        return response()->json([
            'status' => true,
            'message' => __('Success'),
            'googleCalendarTokenExists' => Storage::exists($storageGoogleTokenPath),
            'userId' => $this->user->id,
            'googleAuthUrl' => $googleAuthUrl
        ]);
    }

    /**
     * @return null
     */
    public function getGoogleAuthUrl()
    {
        $client = $this->getGoogleClientWithUncheckedAccessToken($this->user->id);
        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            if (!$client->getRefreshToken()) {
                return $client->createAuthUrl();
            }
        }
        return null;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCalendarToken(Request $request)
    {
        if (!$this->company->google_calendar_connected) {
            return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
        }

        $authCode = $request->code;
        if (empty($authCode)
            || !Str::contains($request->scope, 'email')
            || !Str::contains($request->scope, 'openid')
            || !Str::contains($request->scope, \Google\Service\PeopleService::USERINFO_EMAIL)
            || !Str::contains($request->scope, \Google\Service\Calendar::CALENDAR)
        ) {
            return self::httpBadRequest('Invalid code or scope');
        }

        $storageGoogleTokenPath = FileController::getStorageGoogleTokenPath($this->user->id, $this->company->id);

        if (!Storage::exists($storageGoogleTokenPath)) {
            $client = $this->getGoogleClientWithUncheckedAccessToken($this->user->id);
            if ($client->isAccessTokenExpired()) {
                // Refresh the token if possible, else fetch a new one.
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                } else {
                    // Exchange authorization code for an access token.
                    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                    $client->setAccessToken($accessToken);

                    // Check to see if there was an error.
                    if (array_key_exists('error', $accessToken)) {
                        return response()->json([
                            'status' => false,
                            'message' => __('Something wrong with token'),
                        ], 400);
                    }
                }
                // Save the token to a file.
                Storage::disk('local')->put($storageGoogleTokenPath, json_encode($client->getAccessToken()));
            }
            $tokenInfo = $client->verifyIdToken();
            if (!empty($tokenInfo['email'])) { // this need for use add attendees in google event
                User::where('id', $this->user->id)->update(['google_email' => $tokenInfo['email']]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => __('Success'),
        ]);
    }

    /**
     * @return mixed
     */
    public function removeCalendarToken()
    {
        if (!$this->company->google_calendar_connected) {
            return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
        }

        $storageGoogleTokenPath = FileController::getStorageGoogleTokenPath($this->user->id, $this->company->id);
        if (Storage::exists($storageGoogleTokenPath)) {
            if (Storage::delete($storageGoogleTokenPath)) {
                return response()->json([
                    'status' => true,
                    'message' => __('Success'),
                    'removed' => true,
                ]);
            }
        }

        return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
    }

}
