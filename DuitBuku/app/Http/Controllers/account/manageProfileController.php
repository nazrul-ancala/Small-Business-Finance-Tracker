<?php

namespace App\Http\Controllers\account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class manageProfileController extends Controller
{
    public function index()
    {
        return view('account.profile', ['user' => Auth::user()]);
    }
}
