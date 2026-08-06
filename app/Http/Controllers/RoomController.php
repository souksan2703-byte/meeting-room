<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();

        return view('home', compact('rooms'));
    }

    public function create(){}


    public function store(Request $request){}


    public function show(string $id){}


    public function edit(string $id){}


    public function update(Request $request, string $id){}


    public function destroy(string $id){}
}