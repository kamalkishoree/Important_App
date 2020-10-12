<?php

namespace App\Http\Controllers\Godpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    public function Dashboard()
    {
        return view('godpanel/dashboard');
    }
}
