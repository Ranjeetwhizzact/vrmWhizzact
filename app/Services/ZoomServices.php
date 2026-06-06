<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZoomServices
{
    protected $client;

    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();

        $this->baseUrl =
            'https://api.zoom.us/v2/';
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE ACCESS TOKEN
    |--------------------------------------------------------------------------
    */

    public function generateAccessToken($doctor)
    {
        try {

            Log::info('Zoom Credentials', [

                'doctor_id' => $doctor->id,

                'doctor_email' => $doctor->email,

                'account_id' => $doctor->zoom_account_id,

                'client_id' => $doctor->zoom_client_id,
            ]);

            $response = Http::withBasicAuth(

                $doctor->zoom_client_id,

                $doctor->zoom_client_secret

            )->asForm()->post(

                'https://zoom.us/oauth/token?grant_type=account_credentials&account_id='
                    . $doctor->zoom_account_id
            );

            if (!$response->successful()) {

                Log::error('Zoom token failed', [

                    'doctor_id' => $doctor->id,

                    'response' => $response->body(),
                ]);

                throw new \Exception(
                    'Unable to generate Zoom token'
                );
            }

            $data = $response->json();

            Log::info('Zoom token generated', [

                'doctor_id' => $doctor->id,
            ]);

            return $data['access_token'];
        } catch (\Throwable $e) {

            Log::error('Zoom token exception', [

                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE MEETING
    |--------------------------------------------------------------------------
    */

    public function createMeeting($doctor, $startTime)
    {
        try {

            $token =
                $this->generateAccessToken($doctor);

            $response = Http::withToken($token)
                ->post(
                    'https://api.zoom.us/v2/users/me/meetings',
                    [

                        'topic' =>
                        'Medical Consultation',

                        'type' => 2,

                        'start_time' => $startTime,

                        'duration' => 30,

                        'timezone' => 'Asia/Kolkata',

                        'agenda' =>
                        'Doctor Appointment',

                        'settings' => [

                            'host_video' => true,

                            'participant_video' => true,

                            'waiting_room' => true,

                            'join_before_host' => false,

                            'mute_upon_entry' => true,

                            'auto_recording' => 'cloud',
                        ]
                    ]
                );

            if (!$response->successful()) {

                Log::error('Zoom meeting failed', [

                    'doctor_id' => $doctor->id,

                    'response' => $response->body(),
                ]);

                throw new \Exception(
                    'Unable to create Zoom meeting'
                );
            }

            $meeting = $response->json();

            Log::info('Zoom meeting created', [

                'meeting_id' =>
                $meeting['id'] ?? null,

                'doctor_id' =>
                $doctor->id,
            ]);

            return $meeting;
        } catch (\Throwable $e) {

            Log::error('Zoom meeting exception', [

                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | START CLOUD RECORDING
    |--------------------------------------------------------------------------
    */

    public function startZoomRecording(
        $doctor,
        $meetingId
    ) {

        $accessToken =
            $this->generateAccessToken($doctor);

        $response = Http::withHeaders([

            'Authorization' =>
            'Bearer ' . $accessToken,

            'Content-Type' =>
            'application/json',

        ])->patch(

            "https://api.zoom.us/v2/meetings/{$meetingId}",

            [

                "settings" => [

                    "auto_recording" => "cloud"
                ]
            ]
        );

        return $response->json();
    }

    /*
    |--------------------------------------------------------------------------
    | GET MEETING DETAILS
    |--------------------------------------------------------------------------
    */

    public function getMeetingDetails(
        $doctor,
        $meetingId
    ) {

        $accessToken =
            $this->generateAccessToken($doctor);

        $response = Http::withHeaders([

            'Authorization' =>
            'Bearer ' . $accessToken,

            'Content-Type' =>
            'application/json',

        ])->get(
            "https://api.zoom.us/v2/meetings/{$meetingId}"
        );

        return $response->json();
    }

    /*
    |--------------------------------------------------------------------------
    | END MEETING
    |--------------------------------------------------------------------------
    */

    public function endMeeting(
        $doctor,
        $meetingId
    ) {

        $accessToken =
            $this->generateAccessToken($doctor);

        $response = Http::withHeaders([

            'Authorization' =>
            'Bearer ' . $accessToken,

            'Content-Type' =>
            'application/json',

        ])->put(

            "https://api.zoom.us/v2/meetings/{$meetingId}/status",

            [

                'action' => 'end'
            ]
        );

        return $response->json();
    }

    /*
    |--------------------------------------------------------------------------
    | GET RECORDINGS
    |--------------------------------------------------------------------------
    */

    public function getMeetingRecordings(
        $doctor,
        $meetingId
    ) {

        $accessToken =
            $this->generateAccessToken($doctor);

        $response = Http::withHeaders([

            'Authorization' =>
            'Bearer ' . $accessToken,

            'Content-Type' =>
            'application/json',

        ])->get(

            "https://api.zoom.us/v2/meetings/{$meetingId}/recordings"
        );

        return $response->json();
    }
}
