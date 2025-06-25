<?php

namespace App\Http\Controllers;

use App\Services\ZoomService;

class ZoomController extends Controller
{
    protected $zoomService;

    public function __construct(ZoomService $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    public function createMeeting()
    {
        $meetingData = [
            'topic' => 'Telemedicine Consult',
            'type' => 2, // Scheduled meeting
            'start_time' => now()->addMinutes(10)->toIso8601String(), // ISO 8601 format
            'duration' => 60, // Minutes
            'timezone' => 'UTC',
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'join_before_host' => false,
            ],
        ];

        try {
            $meeting = $this->zoomService->createMeeting($meetingData);
            return response()->json([
                'join_url' => $meeting['join_url'],
                'meeting_id' => $meeting['id'],
                'password' => $meeting['password'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

