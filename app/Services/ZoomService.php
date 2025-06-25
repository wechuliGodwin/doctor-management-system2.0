<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZoomService
{
    protected $accountId;
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl = 'https://api.zoom.us/v2';
    protected $token;

    public function __construct()
    {
        $this->accountId = env('ZOOM_ACCOUNT_ID');
        $this->clientId = env('ZOOM_CLIENT_ID');
        $this->clientSecret = env('ZOOM_CLIENT_SECRET');
        $this->token = $this->generateToken();
    }

    protected function generateToken()
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            //->withOptions(['verify' => false]) // Disable SSL verification
            ->asForm()
            ->post('https://zoom.us/oauth/token', [
                'grant_type' => 'account_credentials',
                'account_id' => $this->accountId,
            ]);

        if ($response->failed()) {
            throw new \Exception('Failed to generate Zoom token: ' . $response->body());
        }

        return $response->json()['access_token'];
    }

    public function createMeeting(array $data)
    {
        $response = Http::withToken($this->token)
            //->withOptions(['verify' => false]) // Disable SSL verification here too
            ->post("{$this->baseUrl}/users/me/meetings", $data);

        if ($response->failed()) {
            throw new \Exception('Failed to create meeting: ' . $response->body());
        }

        return $response->json();
    }
}
