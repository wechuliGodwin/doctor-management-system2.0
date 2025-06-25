<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Show the application home page with recent blogs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Handle language switching
        $lang = $request->query('lang', session('locale', 'en')); // Default to 'en'
        if (in_array($lang, ['en', 'sw', 'fr'])) {
            App::setLocale($lang);
            session(['locale' => $lang]); // Persist the choice in session
        }

        // Track the page view for the home page (url = "") for today
        $today = now()->toDateString(); // e.g., '2025-03-31'
        DB::statement(
            'INSERT INTO page_views (url, view_date, view_count) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE view_count = view_count + 1',
            ['', $today]
        );

        // Fetch recent blogs
        $blogs = Blog::latest()->take(3)->get();

        // Fetch the total view count for the home page (all time)
        $homeViewCount = DB::table('page_views')
            ->where('url', '')
            ->sum('view_count') ?? 0;

        // Optional: Fetch today's view count for the home page
        $todayViewCount = DB::table('page_views')
            ->where('url', '')
            ->where('view_date', $today)
            ->value('view_count') ?? 0;

        return view('home', compact('blogs', 'homeViewCount', 'todayViewCount'));
    }

    /**
     * Show a single blog post.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        
        // Fetch other blogs to display in the sidebar
        $otherBlogs = Blog::where('id', '!=', $id)->latest()->take(5)->get();

        return view('blog.show', compact('blog', 'otherBlogs'));
    }
}