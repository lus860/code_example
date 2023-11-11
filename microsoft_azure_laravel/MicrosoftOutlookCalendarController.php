<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use League\OAuth2\Client\Provider\GenericProvider;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Microsoft\Graph\Model\Entity;
use Microsoft\Graph\Model\Event;

class MicrosoftOutlookCalendarController extends Controller
{
    protected $oauthClient;

    public function createEvent()
    {
        $access_token = self::getAccessToken($this->user->id, $this->company);
        if ($access_token) {
            $graph = new Graph();
            $graph->setAccessToken($access_token);
            $event = new Event();
            $event->setSubject('Meeting with Client test');
            $event->setStart([
                'dateTime' => '2023-11-28T09:00:00',
                'timeZone' => 'UTC', // You should specify the appropriate time zone
            ]);

            $event->setEnd([
                'dateTime' => '2023-11-28T10:00:00',
                'timeZone' => 'UTC', // You should specify the appropriate time zone
            ]);
            $event->setLocation(['displayName' => 'Client Office']);

            $event->setBody([
                'contentType' => 'HTML', // You can use 'Text' or 'HTML' based on your needs
                'content' => 'Meeting details and agenda go here.', // Your description content
            ]);

            $eventResponse = $graph->createRequest("POST", "/me/events")
                ->attachBody($event)
                ->execute();

            if ($eventResponse) {
                return response()->json([
                    'success' => true,
                    'message' => __('Event created successfully!'),
                ], 200);
            }
        }

        return response()->json([
            'success' => false,
            'message' => __('Error requesting access token'),
        ], 400);
    }

    public function deleteEvent()
    {
        $access_token = self::getAccessToken($this->user->id, $this->company);
        if ($access_token) {
            $graph = new Graph();
            $graph->setAccessToken($access_token);
            $eventId = "EVENT_ID_TO_DELETE";

            $eventUrl = "/me/events/$eventId";

            $response = $graph->createRequest("DELETE", $eventUrl)
                ->execute();

            if ($response->getStatus() == 204) {
                return response()->json([
                    'success' => true,
                    'message' => __('Event deleted successfully.'),
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __("Failed to delete event. Status code: " . $response->getStatus()),
                ], 400);
            }
        }

        return response()->json([
            'success' => false,
            'message' => __('Cannot get client!'),
        ], 400);
    }

    public static function getAccessToken($userId, $company)
    {
        $userToken = MicrosoftOutlookCalendar::where('user_id', $userId)->where('id_company', $company->id)->first();

        if (!$userToken) {
            return '';
        }

        if ($userToken->token_expires <= now()) {
            $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId' => config('azure.client_id'),
                'clientSecret' => config('azure.client_secret'),
                'redirectUri' => $company->URL_APM() . '/check-ins',
                'urlAuthorize' => config('azure.authority') . config('azure.authorizeEndpoint'),
                'urlAccessToken' => config('azure.authority') . config('azure.tokenEndpoint'),
                'urlResourceOwnerDetails' => '',
                'scopes' => config('azure.scopes')
            ]);

            try {
                $newToken = $oauthClient->getAccessToken('refresh_token', [
                    'refresh_token' => $userToken->refresh_token
                ]);

                $userToken->update([
                    'access_token' => $newToken->getToken(),
                    'refresh_token' => $newToken->getRefreshToken(),
                    'token_expires' => $newToken->getExpires(),
                ]);

                return $newToken->getToken();
            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                return '';
            }
        }

        return $userToken->access_token;
    }

}
