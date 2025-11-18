<?php

namespace App\Http\Controllers;

use App\Models\DataSubmission;
use Illuminate\Http\Request;

class DataManagementController extends Controller
{
    public function __construct()
    {
        ->middleware('auth');
    }

    public function index()
    {
         = DataSubmission::paginate(15);
        return view('datamanagement', ['submissions' => ]);
    }

    public function store(Request )
    {
         = ->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'dinas_id' => 'required|exists:dinas,id',
            'data' => 'required|json'
        ]);

        DataSubmission::create();
        return redirect()->route('datamanagement')->with('success', 'Data submitted successfully');
    }

    public function update(Request , DataSubmission )
    {
        ->authorize('update', );

         = ->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'data' => 'required|json'
        ]);

        ->update();
        return redirect()->route('datamanagement')->with('success', 'Data updated successfully');
    }
}
