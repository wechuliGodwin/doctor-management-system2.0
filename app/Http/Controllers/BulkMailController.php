<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BulkEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BulkMailController extends Controller
{
    public function index()
    {
        if (session('access_code') !== '131187') {
            return view('suppliers.bulkmail_code');
        }

        try {
            DB::table('page_views')->updateOrInsert(
                ['url' => 'contact', 'view_date' => now()->toDateString()],
                ['view_count' => DB::raw('view_count + 1')]
            );

            DB::table('page_views')->updateOrInsert(
                ['url' => 'telemedicine-patient', 'view_date' => now()->toDateString()],
                ['view_count' => DB::raw('view_count + 1')]
            );

            $suppliers = Supplier::where('emailstatus', 'not sent')->get();

            $pageStats = DB::table('page_views')
                ->select('url', DB::raw('SUM(view_count) as total_views'))
                ->groupBy('url')
                ->orderBy('total_views', 'desc')
                ->get();

            $dailyStats = DB::table('page_views')
                ->select('view_date', DB::raw('SUM(view_count) as total_views'))
                ->where('view_date', '>=', now()->subDays(30))
                ->groupBy('view_date')
                ->orderBy('view_date', 'asc')
                ->get();

            $viewsPerDay = DB::table('page_views')
                ->where('view_date', now()->toDateString())
                ->sum('view_count') ?? 0;

            $viewsPerPageToday = DB::table('page_views')
                ->select('url', DB::raw('SUM(view_count) as total_views'))
                ->where('view_date', now()->toDateString())
                ->groupBy('url')
                ->orderBy('total_views', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error("Failed to load bulkmail index: " . $e->getMessage());
            return view('suppliers.bulkmail')->with('error', 'Failed to load data: ' . $e->getMessage());
        }

        return view('suppliers.bulkmail', compact('suppliers', 'pageStats', 'dailyStats', 'viewsPerDay', 'viewsPerPageToday'));
    }

    public function checkCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $correctCode = '131187';

        if ($request->input('code') === $correctCode) {
            session(['access_code' => $correctCode]);
            return redirect()->route('suppliers.bulkmail')->with('success', 'Access granted!');
        }

        return redirect()->back()->with('error', 'Incorrect code. Please try again.');
    }

    public function send(Request $request)
    {
        if (session('access_code') !== '131187') {
            return redirect()->route('suppliers.bulkmail')->with('error', 'Please enter the correct access code first.');
        }

        $emailsSent = 0;
        $errors = 0;

        try {
            Supplier::where('emailstatus', 'not sent')
                ->take(100)
                ->chunk(100, function ($suppliers) use (&$emailsSent, &$errors) {
                    foreach ($suppliers as $supplier) {
                        try {
                            // Send email only
                            Mail::to($supplier->email)->send(new BulkEmail($supplier));
                            $emailsSent++;

                            if ($supplier->alternate_email) {
                                Mail::to($supplier->alternate_email)->send(new BulkEmail($supplier));
                                $emailsSent++;
                            }

                            $supplier->update(['emailstatus' => 'sent']);
                            Log::info("Processed supplier ID: {$supplier->id} - Email sent.");
                        } catch (\Exception $e) {
                            $errors++;
                            Log::error("Failed to process supplier ID: {$supplier->id}. Error: " . $e->getMessage());
                        }
                    }
                });

            $message = "{$emailsSent} emails sent in this batch!";
            if ($errors > 0) {
                $message .= " {$errors} errors occurred—check logs for details.";
            }

            return redirect()->route('suppliers.bulkmail')->with('success', $message);
        } catch (\Exception $e) {
            Log::error("Bulk mail sending failed: " . $e->getMessage());
            return redirect()->route('suppliers.bulkmail')->with('error', 'Failed to send bulk emails: ' . $e->getMessage());
        }
    }
}