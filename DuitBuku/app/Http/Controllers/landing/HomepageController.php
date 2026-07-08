<?php

namespace App\Http\Controllers\landing;

use App\Http\Controllers\Controller;

class HomepageController extends Controller
{
    public function index()
    {
        return view('landing.homepage');
    }
}
