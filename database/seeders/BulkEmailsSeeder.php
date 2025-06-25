<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;

class BulkEmailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path to the file containing email addresses
        $filePath = base_path('storage/emails/emails_3.csv');

        // Check if the file exists
        if (File::exists($filePath)) {
            // Read the CSV file
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0); // Assuming the first row is the header

            // Fetch all the records as an associative array
            $records = $csv->getRecords();

            $emailData = [];

            foreach ($records as $record) {
                $email = trim($record['email']);
                $name = trim($record['name']);

                // Encode to handle special characters properly
                $email = utf8_encode($email);
                $name = utf8_encode($name);

                // Check if the email already exists and is marked as sent
                $existingEmail = DB::table('bulk_emails')
                    ->where('email', $email)
                    ->first();

                if ($existingEmail) {
                    if (!$existingEmail->email_sent) {
                        // Update name and other info if needed, but do not reset email_sent
                        DB::table('bulk_emails')
                            ->where('id', $existingEmail->id)
                            ->update([
                                'name' => $name,
                                'updated_at' => now(),
                            ]);
                    }
                } else {
                    // Add new emails that aren't already marked as sent
                    $emailData[] = [
                        'email' => $email,
                        'name' => $name,
                        'email_sent' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Insert the new data into the bulk_emails table
            if (!empty($emailData)) {
                DB::table('bulk_emails')->insert($emailData);
                $this->command->info('Bulk emails seeded successfully!');
            } else {
                $this->command->info('No new emails to seed.');
            }
        } else {
            $this->command->error('The file containing emails does not exist.');
        }
    }
}
