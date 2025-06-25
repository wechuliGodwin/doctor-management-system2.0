<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\File;

class FileController extends Controller
{
    public function index()
    {
        // Track the page view for /guidelines
        $today = now()->toDateString();
        DB::statement(
            'INSERT INTO page_views (url, view_date, view_count) 
             VALUES (?, ?, 1) 
             ON DUPLICATE KEY UPDATE view_count = view_count + 1',
            ['guidelines', $today]
        );

        // Fetch files grouped by department (your existing logic)
        $filesByDepartment = File::where('deleted', 0)
            ->orderBy('file_order')
            ->get()
            ->groupBy('department');
        $departments = $filesByDepartment->keys();

        // Fetch today's views for /guidelines
        $todayViews = DB::table('page_views')
            ->where('url', 'guidelines')
            ->where('view_date', $today)
            ->value('view_count') ?? 0;

        // Fetch total views for the last 30 days for /guidelines
        $last30DaysViews = DB::table('page_views')
            ->where('url', 'guidelines')
            ->where('view_date', '>=', now()->subDays(30)->toDateString())
            ->sum('view_count') ?? 0;

        // Pass the data to the guidelines view
        return view('guidelines', compact('filesByDepartment', 'departments', 'todayViews', 'last30DaysViews'));
    }
}