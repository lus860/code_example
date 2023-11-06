<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;

class AzureADLoginV2Controller extends Controller
{
    use HandlesOAuthErrors;

    public function redirectToAzureAD()
    {
        // Generate a URL to Azure AD login page
        $azureADLoginUrl = 'https://login.microsoftonline.com/{your-tenant-id}/oauth2/authorize';
        $clientId = config('azuread.client_id');
        $redirectUri = config('azuread.redirect_uri');
        $scope = 'openid profile email';
        $state = Str::random(40);

        // Store the state in the session to validate it later
        session(['azuread_state' => $state]);

        $url = "$azureADLoginUrl?client_id=$clientId&redirect_uri=$redirectUri&response_type=code&scope=$scope&state=$state";

        return redirect($url);
    }

    public function handleAzureADCallback(Request $request)
    {
        // Retrieve the state from the session
        $state = session('azuread_state');

        // Ensure the state parameter matches the one you set during the initial request
        if ($request->input('state') !== $state) {
            return redirect('/login')->with('error', 'Invalid state parameter');
        }

        // Check if the callback contains an error
        if ($request->filled('error')) {
            return redirect('/login')->with('error', 'Azure AD authentication error: ' . $request->input('error_description'));
        }

        // Retrieve the authorization code from the callback
        $authorizationCode = $request->input('code');

        // Exchange the authorization code for access and refresh tokens
        $tokenResponse = Http::asForm()
            ->post('https://login.microsoftonline.com/{your-tenant-id}/oauth2/token', [
                'grant_type' => 'authorization_code',
                'client_id' => config('azuread.client_id'),
                'client_secret' => config('azuread.client_secret'),
                'code' => $authorizationCode,
                'redirect_uri' => config('azuread.redirect_uri'),
                'resource' => 'https://graph.microsoft.com', // or the resource you need
            ]);

        // Check if the token request was successful
        if ($tokenResponse->successful()) {
            $tokenData = $tokenResponse->json();

            // Store or use the access and refresh tokens as needed
            $accessToken = $tokenData['access_token'];
            $refreshToken = $tokenData['refresh_token'];

            // Use the access token to authenticate the user
            // You can make requests to the Microsoft Graph API with the access token to retrieve user information
            // You can also store the tokens in your application for future use

            // Redirect the user to the appropriate page after successful authentication
            return redirect('/dashboard')->with('success', 'Azure AD authentication successful');
        } else {
            // Handle the case where token retrieval failed
            return redirect('/login')->with('error', 'Unable to retrieve tokens from Azure AD');
        }
    }
}
