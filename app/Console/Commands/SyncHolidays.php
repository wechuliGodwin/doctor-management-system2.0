<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BkHoliday;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SyncHolidays extends Command
{
    protected $signature = 'holidays:sync {year?}';
    protected $description = 'Sync public holidays from Calendarific API for a given year';

    public function handle()
    {
        $year = $this->argument('year') ?? date('Y');
        $apiKey = env('CALENDARIFIC_API_KEY');
        $country = 'KE'; // Kenya, change as needed

        if (!$apiKey) {
            $this->error('Calendarific API key not set in .env');
            return 1;
        }

        $client = new Client();
        try {
            $response = $client->get('https://calendarific.com/api/v2/holidays', [
                'query' => [
                    'api_key' => $apiKey,
                    'country' => $country,
                    'year' => $year,
                    'type' => 'national',
                ],
                'timeout' => 10,
            ]);

            $data = json_decode($response->getBody(), true);

            if (!isset($data['response']['holidays'])) {
                $this->error('No holidays found in API response');
                return 1;
            }

            foreach ($data['response']['holidays'] as $holiday) {
                BkHoliday::updateOrCreate(
                    [
                        'holiday_date' => $holiday['date']['iso'],
                        'name' => $holiday['name'],
                    ],
                    [
                        'type' => 'public',
                        'hospital_branch' => null,
                    ]
                );
            }

            $this->info("Public holidays for $year synced successfully!");
        } catch (RequestException $e) {
            $this->error('Failed to fetch holidays: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}