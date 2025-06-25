<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RedirectController extends Controller
{
    public function telemedicine()
    {
        return Redirect::away('https://kijabehospital.org/telemedicine-patient'); 
    }
}