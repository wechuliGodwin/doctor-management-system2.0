<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChaplaincyController extends Controller
{
    public function index()
    {
        return view('chaplaincy.index');
    }
}