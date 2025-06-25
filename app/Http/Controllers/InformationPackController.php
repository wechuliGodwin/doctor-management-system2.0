<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InformationPackController extends Controller
{
    public function show()
    {
        return view('education.information-pack');
    }
}