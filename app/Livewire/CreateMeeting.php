<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class CreateMeeting extends Component
{

    public $topic;
    public $start_time;
    public $duration;

    public function createMeeting()
    {
        try {
            $clientId = config('zoom.client_id');
            $clientSecret = config('zoom.client_secret');
            $accessToken = $this->getAccessToken($clientId, $clientSecret);

            $response = Http::withToken($accessToken)
                ->post('https://api.zoom.us/v2/users/me/meetings', [
                    'topic' => $this->topic,
                    'type' => 2,
                    'start_time' => $this->start_time,
                    'duration' => $this->duration,
                    'timezone' => 'UTC',
                    'settings' => [
                        'host_video' => true,
                        'participant_video' => true,
                        'join_before_host' => true,
                        'mute_upon_entry' => false,
                        'waiting_room' => false,
                    ],
                ]);

            if ($response->successful()) {
                $meetingLink = $response->json()['join_url'];
                session()->flash('message', "Meeting created! Join link: $meetingLink");
            } else {
                session()->flash('error', 'Failed to create meeting. Please try again.');
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }



    private function getAccessToken($clientId, $clientSecret)
    {
        try {
            $response = Http::asForm()->post('https://zoom.us/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            } else {
                // Handle unsuccessful response
                throw new \Exception('Unable to retrieve access token: ' . $response->json()['error']);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Handle connection errors
            throw new \Exception('Connection error: ' . $e->getMessage());
        }
    }



    public function render(): Application|Factory|View|\Illuminate\View\View
    {
        return view('livewire.create-meeting');
    }
}
