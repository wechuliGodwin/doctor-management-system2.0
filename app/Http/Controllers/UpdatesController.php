<?php

// app/Http/Controllers/UpdatesController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UpdatesController extends Controller
{
    public function index()
    {
        return view('updates'); // This will load resources/views/updates.blade.php
    }
}