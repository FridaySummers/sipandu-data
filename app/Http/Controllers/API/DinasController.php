<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use Illuminate\Http\Request;

class DinasController extends Controller
{
    public function getStatus()
    {
        return response()->json(Dinas::all());
    }

    public function show(Dinas )
    {
        return response()->json();
    }

    public function store(Request )
    {
         = ->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

         = Dinas::create();
        return response()->json(, 201);
    }
}
