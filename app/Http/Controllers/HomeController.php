<?php

namespace App\Http\Controllers;

use App\Models\Room;

class HomeController extends Controller
{
    public function index()
    {
        $rooms = Room::all();

        return view('home', compact('rooms'));
    }
}